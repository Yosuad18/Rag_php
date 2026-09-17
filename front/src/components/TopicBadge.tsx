interface TopicBadgeProps {
  topic: string;
}

const topicColors: Record<string, string> = {
  salary: 'bg-green-100 text-green-800',
  benefits: 'bg-blue-100 text-blue-800',
  process: 'bg-yellow-100 text-yellow-800',
  technical: 'bg-purple-100 text-purple-800',
  culture: 'bg-pink-100 text-pink-800',
  role: 'bg-indigo-100 text-indigo-800',
  general: 'bg-gray-100 text-gray-800',
};

export default function TopicBadge({ topic }: TopicBadgeProps) {
  const colorClass = topicColors[topic] || topicColors.general;

  return (
    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${colorClass}`}>
      {topic.charAt(0).toUpperCase() + topic.slice(1)}
    </span>
  );
}
