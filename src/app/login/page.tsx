'use client';
import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { api } from '@/lib/axios';
import { toast } from 'sonner';

export default function Login() {
  const r = useRouter();
  const [email, setEmail] = useState('employee1@example.com');
  const [password, setPassword] = useState('password');
  const [loading, setLoading] = useState(false);

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);
    try {
      const { data } = await api.post('/auth/login', { email, password });
      localStorage.setItem('access_token', data.token);
      toast.success('Welcome!');
      r.replace('/');
    } catch (err: any) {
      toast.error(err?.response?.data?.message || 'Login failed');
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={onSubmit} className="min-h-screen grid place-items-center p-4 sm:p-6">
      <div className="space-y-3 border p-6 rounded-xl w-full max-w-sm shadow-sm">
        <h1 className="text-xl font-semibold">Employee Login</h1>
        <input className="border rounded-xl px-3 py-2 w-full" value={email} onChange={e => setEmail(e.target.value)} placeholder="Email" />
        <input className="border rounded-xl px-3 py-2 w-full" type="password" value={password} onChange={e => setPassword(e.target.value)} placeholder="Password" />
        <button className="w-full bg-black text-white rounded-xl py-2 disabled:opacity-50" disabled={loading}>
          {loading ? 'Signing in...' : 'Sign in'}
        </button>
      </div>
    </form>
  );
}

