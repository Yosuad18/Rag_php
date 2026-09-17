import api from './axios';
import { Candidate } from './candidates';
import { Interview } from './interviews';

export interface DashboardStats {
  total_candidates: number;
  pending_interviews: number;
  completed_interviews: number;
  total_interviewers: number;
  hired_candidates: number;
  rejected_candidates: number;
  recent_interviews: Interview[];
  upcoming_interviews: Interview[];
}

export const dashboardApi = {
  stats: () =>
    api.get<{ data: DashboardStats }>('/dashboard/stats'),
};