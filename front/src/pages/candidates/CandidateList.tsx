import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { Plus, Pencil, Trash2 } from 'lucide-react';
import DataTable from '../../components/DataTable';
import StatusBadge from '../../components/StatusBadge';
import Modal from '../../components/Modal';
import { candidateApi, Candidate } from '../../api/candidates';

export default function CandidateList() {
  const [candidates, setCandidates] = useState<Candidate[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const [deleteId, setDeleteId] = useState<string | null>(null);

  const loadCandidates = () => {
    setLoading(true);
    candidateApi.list(1, search, statusFilter)
      .then(res => setCandidates(res.data.data))
      .catch(console.error)
      .finally(() => setLoading(false));
  };

  useEffect(() => { loadCandidates(); }, [search, statusFilter]);

  const handleDelete = () => {
    if (!deleteId) return;
    candidateApi.delete(deleteId).then(() => {
      setCandidates(prev => prev.filter(c => c.id !== deleteId));
      setDeleteId(null);
    });
  };

  const columns = [
    {
      key: 'name',
      header: 'Name',
      render: (c: Candidate) => (
        <div>
          <div className="text-sm font-medium text-gray-900">{c.first_name} {c.last_name}</div>
          <div className="text-sm text-gray-500">{c.email}</div>
        </div>
      ),
    },
    { key: 'position', header: 'Position', render: (c: Candidate) => <span className="text-sm text-gray-700">{c.position_applied}</span> },
    { key: 'phone', header: 'Phone', render: (c: Candidate) => <span className="text-sm text-gray-700">{c.phone || '—'}</span> },
    { key: 'status', header: 'Status', render: (c: Candidate) => <StatusBadge status={c.status} variant="candidate" /> },
    {
      key: 'actions',
      header: '',
      render: (c: Candidate) => (
        <div className="text-right text-sm space-x-2">
          <Link to={`/candidates/${c.id}/edit`} className="text-yellow-600 hover:text-yellow-900"><Pencil className="w-4 h-4 inline" /></Link>
          <button onClick={() => setDeleteId(c.id!)} className="text-red-600 hover:text-red-900"><Trash2 className="w-4 h-4 inline" /></button>
        </div>
      ),
    },
  ];

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-semibold">Candidates</h1>
        <Link to="/candidates/new" className="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700 flex items-center gap-2">
          <Plus className="w-4 h-4" /> New Candidate
        </Link>
      </div>

      <div className="flex gap-4 mb-4">
        <input
          type="text"
          placeholder="Search candidates..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="flex-1 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
        />
        <select
          value={statusFilter}
          onChange={(e) => setStatusFilter(e.target.value)}
          className="rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
        >
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="interviewed">Interviewed</option>
          <option value="hired">Hired</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>

      {loading ? (
        <div className="text-center py-12 text-gray-500">Loading...</div>
      ) : (
        <DataTable columns={columns} data={candidates} emptyMessage="No candidates found." />
      )}

      <Modal isOpen={!!deleteId} onClose={() => setDeleteId(null)} title="Delete Candidate" onConfirm={handleDelete}>
        <p className="text-sm text-gray-500">Are you sure you want to delete this candidate? This action cannot be undone.</p>
      </Modal>
    </div>
  );
}