'use client';
import { useEffect } from 'react';
import { useRouter } from 'next/navigation';

export default function Home() {
  const r = useRouter();
  useEffect(() => {
    const t = localStorage.getItem('access_token');
    r.replace(t ? '/home' : '/login');
  }, [r]);
  return null;
}
