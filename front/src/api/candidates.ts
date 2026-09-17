import api from './axios';

export interface Candidate {
  id?: string;
  first_name: string;
  last_name: string;
  email: string;
  phone?: string;
  position_applied: string;
  status: 'pending' | 'interviewed' | 'hired' | 'rejected';
  notes?: string;
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

export const candidateApi = {
  list: (page = 1, search = '', status = '') =>
    api.get<PaginatedResponse<Candidate>>('/candidates', { params: { page, search, status } }),

  get: (id: string) =>
    api.get<{ data: Candidate }>(`/candidates/${id}`),

  create: (data: Candidate) =>
    api.post<{ data: Candidate; message: string }>('/candidates', data),

  update: (id: string, data: Partial<Candidate>) =>
    api.put<{ data: Candidate; message: string }>(`/candidates/${id}`, data),

  delete: (id: string) =>
    api.delete<{ message: string }>(`/candidates/${id}`),
};