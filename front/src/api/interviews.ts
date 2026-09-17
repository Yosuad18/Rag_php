import api from './axios';
import { Candidate } from './candidates';
import { Interviewer } from './interviewers';

export interface Interview {
  id?: string;
  candidate_id: string;
  interviewer_id: string;
  candidate?: Candidate;
  interviewer?: Interviewer;
  scheduled_at: string;
  duration_minutes: number;
  type: 'technical' | 'behavioral' | 'phone' | 'screening';
  status: 'scheduled' | 'completed' | 'cancelled' | 'no_show';
  location?: string;
  feedback?: string;
  rating?: number;
  created_at?: string;
  updated_at?: string;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

export const interviewApi = {
  list: (page = 1, status = '', type = '') =>
    api.get<PaginatedResponse<Interview>>('/interviews', { params: { page, status, type } }),

  get: (id: string) =>
    api.get<{ data: Interview }>(`/interviews/${id}`),

  create: (data: Interview) =>
    api.post<{ data: Interview; message: string }>('/interviews', data),

  update: (id: string, data: Partial<Interview>) =>
    api.put<{ data: Interview; message: string }>(`/interviews/${id}`, data),

  delete: (id: string) =>
    api.delete<{ message: string }>(`/interviews/${id}`),
};