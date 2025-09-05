'use client';
import Shell from '@/components/Shell';
import { useQuery } from '@tanstack/react-query';
import { api } from '@/lib/axios';
import dayjs from 'dayjs';
import { useState } from 'react';

export default function MyAttendancePage(){
  const [from, setFrom] = useState(dayjs().subtract(30,'day').format('YYYY-MM-DD'));
  const [to, setTo] = useState(dayjs().format('YYYY-MM-DD'));
  const [page, setPage] = useState(1);

  const { data, isFetching, refetch } = useQuery({
    queryKey: ['my-logs', from, to, page],
    queryFn: async ()=>{
      const res = await api.get('/attendance/my', { params: { date_from: from, date_to: to, per_page: 20, page }});
      return res.data;
    }
  });

  return (
    <Shell title="My Attendance">
      <div className="space-y-3">
        <div className="flex flex-wrap gap-2">
          <input type="date" value={from} onChange={e=>setFrom(e.target.value)} className="border rounded px-3 py-2" />
          <input type="date" value={to} onChange={e=>setTo(e.target.value)} className="border rounded px-3 py-2" />
          <button onClick={()=>{ setPage(1); refetch(); }} className="px-4 py-2 rounded border">Filter</button>
        </div>

        {isFetching ? <div>Loading...</div> : (
          <div className="overflow-x-auto border rounded-xl">
            <table className="w-full text-sm">
              <thead>
                <tr className="bg-neutral-50">
                  <th className="text-left p-2">Date</th>
                  <th className="text-left p-2">Check In</th>
                  <th className="text-left p-2">Check Out</th>
                  <th className="text-left p-2">Status</th>
                </tr>
              </thead>
              <tbody>
                {data?.data?.map((row:any)=>(
                  <tr key={row.id} className="border-t">
                    <td className="p-2">{row.work_date}</td>
                    <td className="p-2">{row.check_in_at || '-'}</td>
                    <td className="p-2">{row.check_out_at || '-'}</td>
                    <td className="p-2">{row.status}</td>
                  </tr>
                ))}
                {!data?.data?.length && <tr><td colSpan={4} className="p-3 text-center text-neutral-500">No data</td></tr>}
              </tbody>
            </table>
          </div>
        )}

        <div className="flex items-center gap-2">
          <button disabled={page<=1} onClick={()=>setPage(p=>p-1)} className="px-3 py-1.5 rounded border disabled:opacity-50">Prev</button>
          <span>Page {data?.current_page || page}</span>
          <button disabled={!data?.next_page_url} onClick={()=>setPage(p=>p+1)} className="px-3 py-1.5 rounded border disabled:opacity-50">Next</button>
        </div>
      </div>
    </Shell>
  );
}
