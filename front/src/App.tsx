import { Routes, Route } from 'react-router-dom'
import Layout from './components/Layout'
import Dashboard from './pages/Dashboard'
import CandidateList from './pages/candidates/CandidateList'
import CandidateForm from './pages/candidates/CandidateForm'
import InterviewList from './pages/interviews/InterviewList'
import InterviewForm from './pages/interviews/InterviewForm'
import InterviewerList from './pages/interviewers/InterviewerList'
import InterviewerForm from './pages/interviewers/InterviewerForm'

function App() {
  return (
    <Layout>
      <Routes>
        <Route path="/" element={<Dashboard />} />
        <Route path="/candidates" element={<CandidateList />} />
        <Route path="/candidates/new" element={<CandidateForm />} />
        <Route path="/candidates/:id/edit" element={<CandidateForm />} />
        <Route path="/interviews" element={<InterviewList />} />
        <Route path="/interviews/new" element={<InterviewForm />} />
        <Route path="/interviews/:id/edit" element={<InterviewForm />} />
        <Route path="/interviewers" element={<InterviewerList />} />
        <Route path="/interviewers/new" element={<InterviewerForm />} />
        <Route path="/interviewers/:id/edit" element={<InterviewerForm />} />
      </Routes>
    </Layout>
  )
}

export default App