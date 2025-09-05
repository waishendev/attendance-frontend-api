'use client';
export default function Shell({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="max-w-4xl mx-auto p-4 sm:p-6">
      <header className="flex items-center justify-between mb-4">
        <nav className="flex gap-4 text-sm text-neutral-600">
          <a href="/" className="hover:underline">Home</a>
          <a href="/attendance" className="hover:underline">Attendance</a>
          <a href="/leaves" className="hover:underline">Leaves</a>
          <a href="/profile" className="hover:underline">Profile</a>
        </nav>
        <button
          className="px-3 py-1.5 rounded-xl border"
          onClick={() => { localStorage.removeItem('access_token'); location.href='/login'; }}
        >
          Logout
        </button>
      </header>
      <h1 className="text-xl font-semibold mb-6">{title}</h1>
      {children}
    </div>
  );
}
