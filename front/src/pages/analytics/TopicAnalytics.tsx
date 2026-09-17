import { useEffect, useState } from 'react';
import { questionApi, TopicStat, TimelineStat } from '../../api/questions';
import TopicBadge from '../../components/TopicBadge';

export default function TopicAnalytics() {
  const [topicStats, setTopicStats] = useState<TopicStat[]>([]);
  const [timelineStats, setTimelineStats] = useState<TimelineStat[]>([]);
  const [period, setPeriod] = useState<'week' | 'month'>('week');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      try {
        const [topicsRes, timelineRes] = await Promise.all([
          questionApi.analyticsTopics(),
          questionApi.analyticsTimeline(period),
        ]);
        setTopicStats(topicsRes.data.data);
        setTimelineStats(timelineRes.data.data);
      } catch {
        setError('Failed to load analytics.');
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [period]);

  const totalQuestions = topicStats.reduce((sum, stat) => sum + stat.count, 0);
  const maxCount = Math.max(...topicStats.map(s => s.count), 1);

  if (loading) {
    return <div className="text-center py-8 text-gray-500">Loading analytics...</div>;
  }

  if (error) {
    return <div className="text-center py-8 text-red-500">{error}</div>;
  }

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-semibold">Question Analytics</h1>
        <p className="text-gray-500 mt-1">Topic distribution and trends</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-medium mb-4">Topic Distribution</h2>
          <div className="space-y-3">
            {topicStats.map((stat) => (
              <div key={stat.topic} className="flex items-center gap-3">
                <TopicBadge topic={stat.topic} />
                <div className="flex-1">
                  <div className="h-4 bg-gray-100 rounded-full overflow-hidden">
                    <div
                      className="h-full bg-emerald-500 rounded-full"
                      style={{ width: `${(stat.count / maxCount) * 100}%` }}
                    />
                  </div>
                </div>
                <span className="text-sm font-medium text-gray-600 w-12 text-right">
                  {stat.count}
                </span>
              </div>
            ))}
          </div>
          <div className="mt-4 pt-4 border-t border-gray-200">
            <span className="text-sm text-gray-500">Total: {totalQuestions} questions</span>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <div className="flex items-center justify-between mb-4">
            <h2 className="text-lg font-medium">Questions Over Time</h2>
            <select
              value={period}
              onChange={(e) => setPeriod(e.target.value as typeof period)}
              className="text-sm border-gray-300 rounded-md"
            >
              <option value="week">By Week</option>
              <option value="month">By Month</option>
            </select>
          </div>

          {timelineStats.length === 0 ? (
            <div className="text-center text-gray-500 py-8">No data available.</div>
          ) : (
            <div className="space-y-2">
              {timelineStats.map((stat) => (
                <div key={stat.period} className="flex items-center gap-3">
                  <span className="text-sm text-gray-600 w-24">{stat.period}</span>
                  <div className="flex-1">
                    <div className="h-4 bg-gray-100 rounded-full overflow-hidden">
                      <div
                        className="h-full bg-blue-500 rounded-full"
                        style={{
                          width: `${(stat.count / Math.max(...timelineStats.map(s => s.count), 1)) * 100}%`,
                        }}
                      />
                    </div>
                  </div>
                  <span className="text-sm font-medium text-gray-600 w-12 text-right">
                    {stat.count}
                  </span>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
