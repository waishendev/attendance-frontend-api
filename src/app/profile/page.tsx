'use client';
import Shell from '@/components/Shell';
import State from '@/components/State';
import { useQuery } from '@tanstack/react-query';
import { api } from '@/lib/axios';

export default function ProfilePage(){
  const { data, isFetching } = useQuery({
    queryKey: ['me'],
    queryFn: async ()=> (await api.get('/auth/me')).data
  });

  return (
    <Shell title="Profile">
      {isFetching ? <State type="loading" /> : (
        <div className="space-y-2 border rounded-xl p-4 shadow-sm">
          <div><b>Name:</b> {data?.name}</div>
          <div><b>Email:</b> {data?.email}</div>
          <div className="text-sm text-neutral-500">This data comes from `/auth/me`</div>
        </div>
      )}
    </Shell>
  );
}
