import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { Plus, Pencil, Trash2 } from 'lucide-react';
import DataTable from '../../components/DataTable';
import Modal from '../../components/Modal';
import { interviewerApi, Interviewer } from '../../api/interviewers';

export default function InterviewerList() {
  const [interviewers, setInterviewers] = useState<Interviewer[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [deleteId, setDeleteId] = useState<string | null>(null);

  const loadInterviewers = () => {
    setLoading(true);
    interviewerApi.list(1, search)
      .then(res => setInterviewers(res.data.data))
      .catch(console.error)
      .finally(() => setLoading(false));
  };

  useEffect(() => { loadInterviewers(); }, [search]);

  const handleDelete = () => {
    if (!deleteId) return;
    interviewerApi.delete(deleteId).then(() => {
      setInterviewers(prev => prev.filter(i => i.id !== deleteId));
      setDeleteId(null);
    });
  };

  const columns = [
    {
      key: 'name',
      header: 'Name',
      render: (i: Interviewer) => (
        <div>
          <div className="text-sm font-medium text-gray-900">{i.name}</div>
          <div className="text-sm text-gray-500">{i.email}</div>
        </div>
      ),
    },
    { key: 'department', header: 'Department', render: (i: Interviewer) => <span className="text-sm text-gray-700">{i.department}</span> },
    { key: 'role', header: 'Role', render: (i: Interviewer) => <span className="text-sm text-gray-700">{i.role}</span> },
    {
      key: 'actions',
      header: '',
      render: (i: Interviewer) => (
        <div className="text-right text-sm space-x-2">
          <Link to={`/interviewers/${i.id}/edit`} className="text-yellow-600 hover:text-yellow-900"><Pencil className="w-4 h-4 inline" /></Link>
          <button onClick={() => setDeleteId(i.id!)} className="text-red-600 hover:text-red-900"><Trash2 className="w-4 h-4 inline" /></button>
        </div>
      ),
    },
  ];

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-semibold">Interviewers</h1>
        <Link to="/interviewers/new" className="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700 flex items-center gap-2">
          <Plus className="w-4 h-4" /> New Interviewer
        </Link>
      </div>

      <div className="mb-4">
        <input type="text" placeholder="Search interviewers..." value={search} onChange={(e) => setSearch(e.target.value)}
          className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" />
      </div>

      {loading ? (
        <div className="text-center py-12 text-gray-500">Loading...</div>
      ) : (
        <DataTable columns={columns} data={interviewers} emptyMessage="No interviewers found." />
      )}

      <Modal isOpen={!!deleteId} onClose={() => setDeleteId(null)} title="Delete Interviewer" onConfirm={handleDelete}>
        <p className="text-sm text-gray-500">Are you sure you want to delete this interviewer? This action cannot be undone.</p>
      </Modal>
    </div>
  );
}