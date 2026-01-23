PROGRAM CookieProduction
  VAR
    // Inputs (from Factory IO or HMI)
    StartButton AT %IX0.0 : BOOL;
    StopButton AT %IX0.1 : BOOL;
    EmergencyStop AT %IX0.2 : BOOL;
    OvenTempSensor AT %IW0 : INT; // 0-1000 representing 0-250°C
    WeightSensor AT %IW1 : INT; // Dough weight in grams

    // Outputs
    ConveyorMotor AT %QX0.0 : BOOL;
    MixerMotor AT %QX0.1 : BOOL;
    OvenHeater AT %QX0.2 : BOOL;
    AlarmLight AT %QX0.3 : BOOL;
    ExtruderValve AT %QX0.4 : BOOL;

    // Internal Variables
    State : INT := 0; // 0: Idle, 1: Mixing, 2: Extruding, 3: Baking
    TargetTemp : INT := 720; // ~180°C
    TimerMixing : TON;
    TimerBaking : TON;
  END_VAR

  // EMERGENCY LOGIC
  IF EmergencyStop THEN
    ConveyorMotor := FALSE;
    MixerMotor := FALSE;
    OvenHeater := FALSE;
    ExtruderValve := FALSE;
    AlarmLight := TRUE;
    State := 0;
    RETURN;
  END_IF;

  // STOP LOGIC
  IF StopButton THEN
    State := 0;
  END_IF;

  // START LOGIC
  IF StartButton AND State = 0 THEN
    State := 1;
    AlarmLight := FALSE;
  END_IF;

  // STATE MACHINE
  CASE State OF
    0: // Idle
      MixerMotor := FALSE;
      ExtruderValve := FALSE;
      ConveyorMotor := FALSE;
      OvenHeater := FALSE;

    1: // Mixing
      MixerMotor := TRUE;
      TimerMixing(IN := TRUE, PT := T#10s);
      IF TimerMixing.Q THEN
        TimerMixing(IN := FALSE);
        MixerMotor := FALSE;
        State := 2;
      END_IF;

    2: // Extruding (Dough to Belt)
      ExtruderValve := TRUE;
      IF WeightSensor > 500 THEN // Target dough weight reached
        ExtruderValve := FALSE;
        State := 3;
      END_IF;

    3: // Baking & Conveying
      ConveyorMotor := TRUE;
      
      // Temperature Control
      IF OvenTempSensor < TargetTemp THEN
        OvenHeater := TRUE;
      ELSE
        OvenHeater := FALSE;
      END_IF;

      TimerBaking(IN := TRUE, PT := T#30s);
      IF TimerBaking.Q THEN
        TimerBaking(IN := FALSE);
        State := 0; // Cycle complete
      END_IF;
  END_CASE;

END_PROGRAM

CONFIGURATION Config0
  RESOURCE Res0 ON PLC
    TASK TaskMain(INTERVAL := T#50ms, PRIORITY := 0);
    PROGRAM Inst0 WITH TaskMain : CookieProduction;
  END_RESOURCE
END_CONFIGURATION