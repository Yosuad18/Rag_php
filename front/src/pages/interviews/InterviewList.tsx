import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { Plus, Pencil, Trash2 } from 'lucide-react';
import DataTable from '../../components/DataTable';
import StatusBadge from '../../components/StatusBadge';
import Modal from '../../components/Modal';
import { interviewApi, Interview } from '../../api/interviews';

export default function InterviewList() {
  const [interviews, setInterviews] = useState<Interview[]>([]);
  const [loading, setLoading] = useState(true);
  const [statusFilter, setStatusFilter] = useState('');
  const [typeFilter, setTypeFilter] = useState('');
  const [deleteId, setDeleteId] = useState<string | null>(null);

  const loadInterviews = () => {
    setLoading(true);
    interviewApi.list(1, statusFilter, typeFilter)
      .then(res => setInterviews(res.data.data))
      .catch(console.error)
      .finally(() => setLoading(false));
  };

  useEffect(() => { loadInterviews(); }, [statusFilter, typeFilter]);

  const handleDelete = () => {
    if (!deleteId) return;
    interviewApi.delete(deleteId).then(() => {
      setInterviews(prev => prev.filter(i => i.id !== deleteId));
      setDeleteId(null);
    });
  };

  const columns = [
    {
      key: 'candidate',
      header: 'Candidate',
      render: (i: Interview) => (
        <div>
          <div className="text-sm font-medium text-gray-900">
            {i.candidate?.first_name} {i.candidate?.last_name}
          </div>
          <div className="text-sm text-gray-500">{i.candidate?.position_applied}</div>
        </div>
      ),
    },
    {
      key: 'interviewer',
      header: 'Interviewer',
      render: (i: Interview) => <span className="text-sm text-gray-700">{i.interviewer?.name || '—'}</span>,
    },
    {
      key: 'scheduled_at',
      header: 'Date & Time',
      render: (i: Interview) => (
        <div className="text-sm text-gray-700">
          <div>{new Date(i.scheduled_at).toLocaleDateString()}</div>
          <div className="text-gray-500">{new Date(i.scheduled_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
        </div>
      ),
    },
    { key: 'type', header: 'Type', render: (i: Interview) => <StatusBadge status={i.type} /> },
    { key: 'status', header: 'Status', render: (i: Interview) => <StatusBadge status={i.status} /> },
    {
      key: 'actions',
      header: '',
      render: (i: Interview) => (
        <div className="text-right text-sm space-x-2">
          <Link to={`/interviews/${i.id}/edit`} className="text-yellow-600 hover:text-yellow-900"><Pencil className="w-4 h-4 inline" /></Link>
          <button onClick={() => setDeleteId(i.id!)} className="text-red-600 hover:text-red-900"><Trash2 className="w-4 h-4 inline" /></button>
        </div>
      ),
    },
  ];

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-semibold">Interviews</h1>
        <Link to="/interviews/new" className="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700 flex items-center gap-2">
          <Plus className="w-4 h-4" /> Schedule Interview
        </Link>
      </div>

      <div className="flex gap-4 mb-4">
        <select value={statusFilter} onChange={(e) => setStatusFilter(e.target.value)}
          className="rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
          <option value="">All Status</option>
          <option value="scheduled">Scheduled</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
          <option value="no_show">No Show</option>
        </select>
        <select value={typeFilter} onChange={(e) => setTypeFilter(e.target.value)}
          className="rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
          <option value="">All Types</option>
          <option value="technical">Technical</option>
          <option value="behavioral">Behavioral</option>
          <option value="phone">Phone</option>
          <option value="screening">Screening</option>
        </select>
      </div>

      {loading ? (
        <div className="text-center py-12 text-gray-500">Loading...</div>
      ) : (
        <DataTable columns={columns} data={interviews} emptyMessage="No interviews found." />
      )}

      <Modal isOpen={!!deleteId} onClose={() => setDeleteId(null)} title="Delete Interview" onConfirm={handleDelete}>
        <p className="text-sm text-gray-500">Are you sure you want to delete this interview? This action cannot be undone.</p>
      </Modal>
    </div>
  );
}