import './globals.css';
import type { Metadata } from 'next';
import QueryProvider from '@/lib/query';
import { Toaster } from 'sonner';

export const metadata: Metadata = { title: 'Attendance FE', description: 'Employee self-service' };

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body className="min-h-screen bg-background text-foreground">
        <QueryProvider>
          {children}
          <Toaster richColors closeButton />
        </QueryProvider>
      </body>
    </html>
  );
}
