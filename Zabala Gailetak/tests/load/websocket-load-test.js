import ws from 'k6/ws';
import { check } from 'k6';

export let options = {
  stages: [
    { duration: '1m', target: 50 },
    { duration: '5m', target: 50 },
    { duration: '1m', target: 0 },
  ],
  maxVUs: 100,
};

export default function () {
  let url = 'ws://localhost:3000/api/ws';
  let res = ws.connect(url, {});

  check(res, { 'websocket connected': (r) => r && r.status === 101 });
  
  res.close();
}
