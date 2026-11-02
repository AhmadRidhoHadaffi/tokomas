import { useEffect, useState } from 'react';

export function useSimulatedLoading(duration = 550): boolean {
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const timer = window.setTimeout(() => setLoading(false), duration);
    return () => window.clearTimeout(timer);
  }, [duration]);

  return loading;
}