PROGRAM CookieProduction
  VAR
    StartButton : BOOL;
    StopButton : BOOL;
    EmergencyStop : BOOL;
    OvenTemperature : REAL;
    ConveyorSpeed : REAL;
    OvenHeater : BOOL;
    ConveyorMotor : BOOL;
    AlarmLight : BOOL;
  END_VAR
  
  IF EmergencyStop THEN
    OvenHeater := FALSE;
    ConveyorMotor := FALSE;
    AlarmLight := TRUE;
    RETURN;
  END_IF;
  
  IF StartButton AND NOT StopButton THEN
    IF OvenTemperature < 180 THEN
      OvenHeater := TRUE;
    ELSE
      OvenHeater := FALSE;
    END_IF;
    ConveyorMotor := TRUE;
  ELSE
    OvenHeater := FALSE;
    ConveyorMotor := FALSE;
  END_IF;
END_PROGRAM
