import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { questionApi, Question } from '../../api/questions';
import { candidateApi, Candidate } from '../../api/candidates';
import QuestionForm from '../../components/QuestionForm';
import TopicBadge from '../../components/TopicBadge';

export default function CandidateQuestions() {
  const { id } = useParams<{ id: string }>();
  const [candidate, setCandidate] = useState<Candidate | null>(null);
  const [questions, setQuestions] = useState<Question[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const fetchData = async () => {
    if (!id) return;

    setLoading(true);
    try {
      const [candidateRes, questionsRes] = await Promise.all([
        candidateApi.get(id),
        questionApi.byCandidate(id),
      ]);
      setCandidate(candidateRes.data.data);
      setQuestions(questionsRes.data.data);
    } catch {
      setError('Failed to load data.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, [id]);

  const handleStatusUpdate = async (question: Question, newStatus: string) => {
    try {
      await questionApi.updateStatus(question.id, question.created_at, newStatus);
      setQuestions(prev =>
        prev.map(q =>
          q.id === question.id ? { ...q, status: newStatus as Question['status'] } : q
        )
      );
    } catch {
      setError('Failed to update question status.');
    }
  };

  const handleDelete = async (question: Question) => {
    if (!confirm('Are you sure you want to delete this question?')) return;

    try {
      await questionApi.delete(question.id, question.created_at);
      setQuestions(prev => prev.filter(q => q.id !== question.id));
    } catch {
      setError('Failed to delete question.');
    }
  };

  if (loading) {
    return <div className="text-center py-8 text-gray-500">Loading...</div>;
  }

  if (error) {
    return <div className="text-center py-8 text-red-500">{error}</div>;
  }

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-semibold">Candidate Questions</h1>
        {candidate && (
          <p className="text-gray-500 mt-1">
            {candidate.first_name} {candidate.last_name} - {candidate.position_applied}
          </p>
        )}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2">
          <div className="bg-white rounded-lg shadow">
            <div className="px-6 py-4 border-b border-gray-200">
              <h2 className="text-lg font-medium">Questions ({questions.length})</h2>
            </div>

            {questions.length === 0 ? (
              <div className="p-6 text-center text-gray-500">No questions yet.</div>
            ) : (
              <div className="divide-y divide-gray-200">
                {questions.map((question) => (
                  <div key={question.id} className="p-6">
                    <div className="flex items-start justify-between">
                      <div className="flex-1">
                        <p className="text-gray-900">{question.question_text}</p>
                        <div className="mt-2 flex items-center gap-2">
                          <TopicBadge topic={question.topic} />
                          <span className="text-xs text-gray-500">
                            {new Date(question.created_at).toLocaleDateString()}
                          </span>
                          <span className="text-xs text-gray-500">via {question.source}</span>
                        </div>
                      </div>

                      <div className="flex items-center gap-2 ml-4">
                        {question.status === 'pending' && (
                          <button
                            onClick={() => handleStatusUpdate(question, 'answered')}
                            className="text-xs px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200"
                          >
                            Mark Answered
                          </button>
                        )}
                        <button
                          onClick={() => handleDelete(question)}
                          className="text-xs px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200"
                        >
                          Delete
                        </button>
                      </div>
                    </div>

                    <div className="mt-2">
                      <span className={`text-xs px-2 py-1 rounded ${
                        question.status === 'answered'
                          ? 'bg-green-100 text-green-700'
                          : question.status === 'archived'
                          ? 'bg-gray-100 text-gray-700'
                          : 'bg-yellow-100 text-yellow-700'
                      }`}>
                        {question.status}
                      </span>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>

        <div>
          {id && <QuestionForm candidateId={id} onSuccess={fetchData} />}
        </div>
      </div>
    </div>
  );
}
