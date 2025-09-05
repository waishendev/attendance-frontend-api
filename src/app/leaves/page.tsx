'use client';
import Shell from '@/components/Shell';
import State from '@/components/State';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { api } from '@/lib/axios';
import { useState } from 'react';
import dayjs from 'dayjs';
import { toast } from 'sonner';

export default function MyLeavesPage(){
  const qc = useQueryClient();
  const [status,setStatus] = useState('');
  const [page,setPage] = useState(1);

  const { data, isFetching } = useQuery({
    queryKey: ['my-leaves', status, page],
    queryFn: async ()=>{
      const res = await api.get('/leaves/my', { params: { status, per_page: 20, page }});
      return res.data;
    }
  });

  const createLeave = useMutation({
    mutationFn: async (payload: any)=> (await api.post('/leaves', payload)).data,
    onSuccess: ()=>{ toast.success('Leave requested'); qc.invalidateQueries({queryKey:['my-leaves']}); }
  });

  const cancelLeave = useMutation({
    mutationFn: async (id: number)=> (await api.put(`/leaves/${id}/cancel`)).data,
    onSuccess: ()=>{ toast.success('Cancelled'); qc.invalidateQueries({queryKey:['my-leaves']}); }
  });

  // 简单的新建表单
  const [type,setType] = useState('annual');
  const [startAt,setStartAt] = useState(dayjs().add(1,'day').format('YYYY-MM-DD')+' 09:00:00');
  const [endAt,setEndAt] = useState(dayjs().add(1,'day').format('YYYY-MM-DD')+' 18:00:00');
  const [reason,setReason] = useState('');

  return (
    <Shell title="My Leaves">
      <div className="space-y-4">
        <div className="flex flex-wrap gap-2">
          <select value={status} onChange={e=>{setStatus(e.target.value); setPage(1);}} className="border rounded-xl px-3 py-2">
            <option value="">All</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <div className="border rounded-xl p-4 space-y-2 shadow-sm">
          <div className="font-medium">New Leave</div>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-2">
            <select value={type} onChange={e=>setType(e.target.value)} className="border rounded-xl px-3 py-2">
              <option value="annual">Annual</option>
              <option value="sick">Sick</option>
              <option value="unpaid">Unpaid</option>
              <option value="other">Other</option>
            </select>
            <input value={startAt} onChange={e=>setStartAt(e.target.value)} className="border rounded-xl px-3 py-2" placeholder="YYYY-MM-DD HH:mm:ss"/>
            <input value={endAt} onChange={e=>setEndAt(e.target.value)} className="border rounded-xl px-3 py-2" placeholder="YYYY-MM-DD HH:mm:ss"/>
            <input value={reason} onChange={e=>setReason(e.target.value)} className="border rounded-xl px-3 py-2" placeholder="Reason(optional)"/>
          </div>
          <button
            className="px-4 py-2 rounded-xl bg-black text-white"
            onClick={()=>createLeave.mutate({ type, start_at: startAt, end_at: endAt, reason })}
          >
            Submit
          </button>
        </div>

        {isFetching ? <State type="loading" /> : (
          <div className="overflow-x-auto border rounded-xl shadow-sm">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-neutral-50">
                  <th className="text-left p-2">ID</th>
                  <th className="text-left p-2">Type</th>
                  <th className="text-left p-2">Start</th>
                  <th className="text-left p-2">End</th>
                  <th className="text-left p-2">Status</th>
                  <th className="text-left p-2">Action</th>
                </tr>
              </thead>
              <tbody>
                {data?.data?.map((row:any)=>(
                  <tr key={row.id} className="border-t">
                    <td className="p-2">{row.id}</td>
                    <td className="p-2">{row.type}</td>
                    <td className="p-2">{row.start_at}</td>
                    <td className="p-2">{row.end_at}</td>
                    <td className="p-2">{row.status}</td>
                    <td className="p-2">
                      {row.status==='pending' ? (
                        <button onClick={()=>cancelLeave.mutate(row.id)} className="px-3 py-1.5 rounded-xl border">Cancel</button>
                      ) : <span className="text-neutral-400">-</span>}
                    </td>
                  </tr>
                ))}
                {!data?.data?.length && (
                  <tr>
                    <td colSpan={6}><State type="empty" /></td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        )}

        <div className="flex items-center gap-2">
          <button disabled={page<=1} onClick={()=>setPage(p=>p-1)} className="px-3 py-1.5 rounded-xl border disabled:opacity-50">Prev</button>
          <span>Page {data?.current_page || page}</span>
          <button disabled={!data?.next_page_url} onClick={()=>setPage(p=>p+1)} className="px-3 py-1.5 rounded-xl border disabled:opacity-50">Next</button>
        </div>
      </div>
    </Shell>
  );
}
