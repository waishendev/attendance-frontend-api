'use client';
import Shell from '@/components/Shell';
import State from '@/components/State';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { api } from '@/lib/axios';
import dayjs from 'dayjs';
import { toast } from 'sonner';

export default function HomePage(){
  const qc = useQueryClient();
  const today = dayjs().format('YYYY-MM-DD');

  const { data, isFetching } = useQuery({
    queryKey: ['my-today', today],
    queryFn: async ()=>{
      const res = await api.get('/attendance/my', { params: { date_from: today, date_to: today, per_page: 1 }});
      return res.data;
    }
  });

  const checkIn = useMutation({
    mutationFn: async ()=> (await api.post('/attendance/check-in')).data,
    onSuccess: ()=>{ toast.success('Checked in'); qc.invalidateQueries({queryKey:['my-today', today]}); }
  });
  const checkOut = useMutation({
    mutationFn: async ()=> (await api.post('/attendance/check-out')).data,
    onSuccess: ()=>{ toast.success('Checked out'); qc.invalidateQueries({queryKey:['my-today', today]}); }
  });

  const log = data?.data?.[0];

  return (
    <Shell title="Home">
      <div className="space-y-4">
        <div className="text-lg font-semibold">Today: {today}</div>
        {isFetching ? <State type="loading" /> : (
          <div className="space-y-2 border rounded-xl p-4 shadow-sm">
            <div>Check In: <b>{log?.check_in_at || '-'}</b></div>
            <div>Check Out: <b>{log?.check_out_at || '-'}</b></div>
            <div>Status: <b>{log?.status || 'N/A'}</b></div>
          </div>
        )}
        <div className="flex gap-2">
          <button onClick={()=>checkIn.mutate()} className="px-4 py-2 rounded-xl bg-black text-white">Check In</button>
          <button onClick={()=>checkOut.mutate()} className="px-4 py-2 rounded-xl border">Check Out</button>
        </div>
      </div>
    </Shell>
  );
}
