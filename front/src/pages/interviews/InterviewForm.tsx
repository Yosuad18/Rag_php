import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { interviewApi, Interview } from '../../api/interviews';
import { candidateApi, Candidate } from '../../api/candidates';
import { interviewerApi, Interviewer } from '../../api/interviewers';

export default function InterviewForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const isEdit = !!id;

  const [form, setForm] = useState<Interview>({
    candidate_id: '',
    interviewer_id: '',
    scheduled_at: '',
    duration_minutes: 60,
    type: 'technical',
    status: 'scheduled',
    location: '',
    feedback: '',
    rating: undefined,
  });
  const [candidates, setCandidates] = useState<Candidate[]>([]);
  const [interviewers, setInterviewers] = useState<Interviewer[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    Promise.all([
      candidateApi.list(1, ''),
      interviewerApi.list(1, ''),
    ]).then(([cRes, iRes]) => {
      setCandidates(cRes.data.data);
      setInterviewers(iRes.data.data);
    });

    if (isEdit) {
      interviewApi.get(id!).then(res => {
        const data = res.data.data;
        setForm({
          ...data,
          scheduled_at: data.scheduled_at ? new Date(data.scheduled_at).toISOString().slice(0, 16) : '',
        });
      });
    }
  }, [id, isEdit]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      if (isEdit) {
        await interviewApi.update(id!, form);
      } else {
        await interviewApi.create(form);
      }
      navigate('/interviews');
    } catch (err: any) {
      setError(err.response?.data?.message || 'An error occurred.');
    } finally {
      setLoading(false);
    }
  };

  const updateField = (field: keyof Interview, value: string | number | undefined) => {
    setForm(prev => ({ ...prev, [field]: value }));
  };

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-semibold">{isEdit ? 'Edit Interview' : 'Schedule Interview'}</h1>
      </div>

      <div className="bg-white rounded-lg shadow p-6 max-w-lg">
        {error && <div className="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm">{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label className="block text-sm font-medium text-gray-700 mb-1">Candidate</label>
            <select value={form.candidate_id} onChange={(e) => updateField('candidate_id', e.target.value)}
              className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
              <option value="">Select candidate</option>
              {candidates.map(c => (
                <option key={c.id} value={c.id}>{c.first_name} {c.last_name} — {c.position_applied}</option>
              ))}
            </select>
          </div>

          <div className="mb-4">
            <label className="block text-sm font-medium text-gray-700 mb-1">Interviewer</label>
            <select value={form.interviewer_id} onChange={(e) => updateField('interviewer_id', e.target.value)}
              className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
              <option value="">Select interviewer</option>
              {interviewers.map(i => (
                <option key={i.id} value={i.id}>{i.name} — {i.department}</option>
              ))}
            </select>
          </div>

          <div className="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
              <input type="datetime-local" value={form.scheduled_at} onChange={(e) => updateField('scheduled_at', e.target.value)}
                className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Duration (min)</label>
              <input type="number" value={form.duration_minutes} onChange={(e) => updateField('duration_minutes', parseInt(e.target.value))}
                className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" min={15} max={480} required />
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Type</label>
              <select value={form.type} onChange={(e) => updateField('type', e.target.value)}
                className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                <option value="technical">Technical</option>
                <option value="behavioral">Behavioral</option>
                <option value="phone">Phone</option>
                <option value="screening">Screening</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select value={form.status} onChange={(e) => updateField('status', e.target.value)}
                className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                <option value="scheduled">Scheduled</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="no_show">No Show</option>
              </select>
            </div>
          </div>

          <div className="mb-4">
            <label className="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input type="text" value={form.location || ''} onChange={(e) => updateField('location', e.target.value)}
              className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Office, Zoom link, etc." />
          </div>

          {isEdit && (
            <>
              <div className="mb-4">
                <label className="block text-sm font-medium text-gray-700 mb-1">Rating (1-5)</label>
                <input type="number" value={form.rating || ''} onChange={(e) => updateField('rating', e.target.value ? parseInt(e.target.value) : undefined)}
                  className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" min={1} max={5} />
              </div>
              <div className="mb-4">
                <label className="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                <textarea value={form.feedback || ''} onChange={(e) => updateField('feedback', e.target.value)} rows={3}
                  className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" />
              </div>
            </>
          )}

          <div className="flex justify-end space-x-3">
            <button type="button" onClick={() => navigate('/interviews')}
              className="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</button>
            <button type="submit" disabled={loading}
              className="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700 disabled:opacity-50">
              {loading ? 'Saving...' : isEdit ? 'Update' : 'Schedule'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}