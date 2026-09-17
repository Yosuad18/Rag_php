interface StatusBadgeProps {
  status: string;
  variant?: 'candidate' | 'interview' | 'type';
}

const statusConfig: Record<string, { bg: string; text: string; label: string }> = {
  pending: { bg: 'bg-yellow-100', text: 'text-yellow-800', label: 'Pending' },
  interviewed: { bg: 'bg-blue-100', text: 'text-blue-800', label: 'Interviewed' },
  hired: { bg: 'bg-green-100', text: 'text-green-800', label: 'Hired' },
  rejected: { bg: 'bg-red-100', text: 'text-red-800', label: 'Rejected' },
  scheduled: { bg: 'bg-indigo-100', text: 'text-indigo-800', label: 'Scheduled' },
  completed: { bg: 'bg-green-100', text: 'text-green-800', label: 'Completed' },
  cancelled: { bg: 'bg-gray-100', text: 'text-gray-800', label: 'Cancelled' },
  no_show: { bg: 'bg-red-100', text: 'text-red-800', label: 'No Show' },
  technical: { bg: 'bg-purple-100', text: 'text-purple-800', label: 'Technical' },
  behavioral: { bg: 'bg-blue-100', text: 'text-blue-800', label: 'Behavioral' },
  phone: { bg: 'bg-teal-100', text: 'text-teal-800', label: 'Phone' },
  screening: { bg: 'bg-orange-100', text: 'text-orange-800', label: 'Screening' },
};

export default function StatusBadge({ status }: StatusBadgeProps) {
  const config = statusConfig[status] || { bg: 'bg-gray-100', text: 'text-gray-800', label: status };

  return (
    <span className={`px-2 py-1 text-xs rounded-full font-medium ${config.bg} ${config.text}`}>
      {config.label}
    </span>
  );
}