'use client';
type StateType = 'loading' | 'empty' | 'error';
export default function State({ type, message }: { type: StateType; message?: string }) {
  const defaults: Record<StateType, string> = {
    loading: 'Loading...',
    empty: 'No data',
    error: 'Something went wrong',
  };
  const colors: Record<StateType, string> = {
    loading: 'text-neutral-500',
    empty: 'text-neutral-500',
    error: 'text-red-500',
  };
  return <div className={`p-4 text-center ${colors[type]}`}>{message || defaults[type]}</div>;
}
