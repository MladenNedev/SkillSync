import axios from 'axios';

const http = axios.create({
  baseURL: 'http://localhost:8080',
  withCredentials: true,
  withXSRFToken: true,
  xsrfCookieName: 'XSRF-TOKEN',   // cookie name from Sanctum
  xsrfHeaderName: 'X-XSRF-TOKEN', // header Laravel checks
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

export default http;