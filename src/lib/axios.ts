import axios from 'axios';

export const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
  timeout: 15000,
});

api.interceptors.request.use(cfg=>{
  if (typeof window !== 'undefined') {
    const t = localStorage.getItem('access_token');
    if (t) cfg.headers.Authorization = `Bearer ${t}`;
  }
  return cfg;
});

api.interceptors.response.use(r=>r, err=>{
  if (err?.response?.status === 401 && typeof window !== 'undefined') {
    localStorage.removeItem('access_token');
    location.href = '/login';
  }
  return Promise.reject(err);
});
