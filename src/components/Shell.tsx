'use client';
export default function Shell({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="max-w-4xl mx-auto p-6">
      <header className="flex items-center justify-between mb-6">
        <h1 className="text-xl font-semibold">{title}</h1>
        <button
          className="px-3 py-1.5 rounded border"
          onClick={() => { localStorage.removeItem('access_token'); location.href='/login'; }}
        >
          Logout
        </button>
      </header>
      {children}
    </div>
  );
}
