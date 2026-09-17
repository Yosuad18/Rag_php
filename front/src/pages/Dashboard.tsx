import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { Users, Calendar, UserCheck, TrendingUp } from 'lucide-react';
import StatsCard from '../components/StatsCard';
import StatusBadge from '../components/StatusBadge';
import { dashboardApi, DashboardStats } from '../api/dashboard';

export default function Dashboard() {
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    dashboardApi.stats()
      .then(res => setStats(res.data.data))
      .catch(console.error)
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return <div className="text-center py-12 text-gray-500">Loading...</div>;
  }

  if (!stats) {
    return <div className="text-center py-12 text-gray-500">Failed to load dashboard data.</div>;
  }

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-semibold">Dashboard</h1>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatsCard title="Total Candidates" value={stats.total_candidates} icon={Users} color="text-emerald-600" />
        <StatsCard title="Pending Interviews" value={stats.pending_interviews} icon={Calendar} color="text-indigo-600" />
        <StatsCard title="Completed Interviews" value={stats.completed_interviews} icon={TrendingUp} color="text-green-600" />
        <StatsCard title="Interviewers" value={stats.total_interviewers} icon={UserCheck} color="text-teal-600" />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-white rounded-lg shadow p-6">
          <div className="flex justify-between items-center mb-4">
            <h2 className="text-lg font-semibold">Upcoming Interviews</h2>
            <Link to="/interviews" className="text-sm text-emerald-600 hover:text-emerald-700">View all</Link>
          </div>
          {stats.upcoming_interviews.length === 0 ? (
            <p className="text-gray-500 text-sm">No upcoming interviews.</p>
          ) : (
            <div className="space-y-3">
              {stats.upcoming_interviews.map((interview) => (
                <div key={interview.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div>
                    <p className="text-sm font-medium text-gray-900">
                      {interview.candidate?.first_name} {interview.candidate?.last_name}
                    </p>
                    <p className="text-xs text-gray-500">
                      {new Date(interview.scheduled_at).toLocaleDateString()} at{' '}
                      {new Date(interview.scheduled_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                    </p>
                  </div>
                  <StatusBadge status={interview.type} />
                </div>
              ))}
            </div>
          )}
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <div className="flex justify-between items-center mb-4">
            <h2 className="text-lg font-semibold">Recent Interviews</h2>
            <Link to="/interviews" className="text-sm text-emerald-600 hover:text-emerald-700">View all</Link>
          </div>
          {stats.recent_interviews.length === 0 ? (
            <p className="text-gray-500 text-sm">No recent interviews.</p>
          ) : (
            <div className="space-y-3">
              {stats.recent_interviews.map((interview) => (
                <div key={interview.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div>
                    <p className="text-sm font-medium text-gray-900">
                      {interview.candidate?.first_name} {interview.candidate?.last_name}
                    </p>
                    <p className="text-xs text-gray-500">
                      with {interview.interviewer?.name}
                    </p>
                  </div>
                  <StatusBadge status={interview.status} />
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}