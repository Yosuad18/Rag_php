import api from './axios';

export interface Interviewer {
  id?: string;
  name: string;
  email: string;
  department: string;
  role: string;
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

export const interviewerApi = {
  list: (page = 1, search = '') =>
    api.get<PaginatedResponse<Interviewer>>('/interviewers', { params: { page, search } }),

  get: (id: string) =>
    api.get<{ data: Interviewer }>(`/interviewers/${id}`),

  create: (data: Interviewer) =>
    api.post<{ data: Interviewer; message: string }>('/interviewers', data),

  update: (id: string, data: Partial<Interviewer>) =>
    api.put<{ data: Interviewer; message: string }>(`/interviewers/${id}`, data),

  delete: (id: string) =>
    api.delete<{ message: string }>(`/interviewers/${id}`),
};