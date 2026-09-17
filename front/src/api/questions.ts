import api from './axios';

export interface Question {
  id: string;
  candidate_id: string;
  question_text: string;
  source: 'web' | 'api' | 'email';
  topic: 'salary' | 'benefits' | 'process' | 'technical' | 'culture' | 'role' | 'general';
  status: 'pending' | 'answered' | 'archived';
  created_at: string;
  metadata?: Record<string, unknown>;
}

export interface TopicStat {
  count: number;
  topic: string;
}

export interface TimelineStat {
  period: string;
  count: number;
}

export const questionApi = {
  list: (limit = 25, topic = '', status = '') =>
    api.get<{ data: Question[] }>('/questions', { params: { limit, topic, status } }),

  get: (id: string, createdAt: string) =>
    api.get<{ data: Question }>(`/questions/${id}`, { params: { created_at: createdAt } }),

  create: (data: { candidate_id: string; question_text: string; source?: string; topic?: string; metadata?: Record<string, unknown> }) =>
    api.post<{ data: Question; message: string }>('/questions', data),

  updateStatus: (id: string, createdAt: string, status: string) =>
    api.put<{ data: Question; message: string }>(`/questions/${id}/status`, { status, created_at: createdAt }),

  delete: (id: string, createdAt: string) =>
    api.delete<{ message: string }>(`/questions/${id}`, { params: { created_at: createdAt } }),

  byCandidate: (candidateId: string) =>
    api.get<{ data: Question[] }>(`/candidates/${candidateId}/questions`),

  analyticsTopics: () =>
    api.get<{ data: TopicStat[] }>('/questions/analytics/topics'),

  analyticsTimeline: (period = 'week') =>
    api.get<{ data: TimelineStat[] }>('/questions/analytics/timeline', { params: { period } }),
};
