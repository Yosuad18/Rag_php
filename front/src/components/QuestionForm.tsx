import { useState } from 'react';
import { questionApi } from '../../api/questions';

interface QuestionFormProps {
  candidateId: string;
  onSuccess?: () => void;
}

export default function QuestionForm({ candidateId, onSuccess }: QuestionFormProps) {
  const [questionText, setQuestionText] = useState('');
  const [source, setSource] = useState<'web' | 'api' | 'email'>('web');
  const [topic, setTopic] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess(false);

    try {
      await questionApi.create({
        candidate_id: candidateId,
        question_text: questionText,
        source,
        topic: topic || undefined,
      });

      setQuestionText('');
      setTopic('');
      setSuccess(true);
      onSuccess?.();
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to submit question.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white rounded-lg shadow p-6">
      <h3 className="text-lg font-medium mb-4">Submit a Question</h3>

      {error && (
        <div className="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm">
          {error}
        </div>
      )}

      {success && (
        <div className="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200 text-green-800 text-sm">
          Question submitted successfully.
        </div>
      )}

      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <label className="block text-sm font-medium text-gray-700 mb-1">Question</label>
          <textarea
            value={questionText}
            onChange={(e) => setQuestionText(e.target.value)}
            rows={4}
            className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
            placeholder="Enter the candidate's question..."
            required
          />
        </div>

        <div className="grid grid-cols-2 gap-4 mb-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Source</label>
            <select
              value={source}
              onChange={(e) => setSource(e.target.value as typeof source)}
              className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
            >
              <option value="web">Web Form</option>
              <option value="api">API</option>
              <option value="email">Email</option>
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Topic (optional)</label>
            <select
              value={topic}
              onChange={(e) => setTopic(e.target.value)}
              className="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
            >
              <option value="">Auto-detect</option>
              <option value="salary">Salary</option>
              <option value="benefits">Benefits</option>
              <option value="process">Process</option>
              <option value="technical">Technical</option>
              <option value="culture">Culture</option>
              <option value="role">Role</option>
              <option value="general">General</option>
            </select>
          </div>
        </div>

        <div className="flex justify-end">
          <button
            type="submit"
            disabled={loading}
            className="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700 disabled:opacity-50"
          >
            {loading ? 'Submitting...' : 'Submit Question'}
          </button>
        </div>
      </form>
    </div>
  );
}
