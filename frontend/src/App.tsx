import {BrowserRouter,Routes,Route,Navigate} from 'react-router-dom'
import {AuthProvider} from './context/AuthContext'
import ProtectedRoute from './components/ProtectedRoute'
import Layout from './components/layout/Layout'
import Landing from './pages/Landing'
import SignIn from './pages/SignIn'
import SignUp from './pages/SignUp'
import Dashboard from './pages/Dashboard'
import Tasks from './pages/Tasks'
import SLA from './pages/SLA'
import Workload from './pages/Workload'
import Reports from './pages/Reports'
import Automation from './pages/Automation'
import ResourcePage from './pages/ResourcePage'
import Simple from './pages/Simple'

export default function App(){return <AuthProvider><BrowserRouter><Routes>
<Route path="/" element={<Landing/>}/><Route path="/signin" element={<SignIn/>}/><Route path="/signup" element={<SignUp/>}/>
<Route element={<ProtectedRoute/>}><Route element={<Layout/>}>
<Route path="/app" element={<Navigate to="/dashboard" replace/>}/><Route path="/dashboard" element={<Dashboard/>}/><Route path="/tasks" element={<Tasks/>}/><Route path="/sla" element={<SLA/>}/><Route path="/workload" element={<Workload/>}/><Route path="/reports" element={<Reports/>}/>
<Route path="/clients" element={<ResourcePage resource="clients" title="Clients" description="Manage BPO clients and client contacts." fields={[{name:'name',label:'Name',required:true},{name:'code',label:'Code'},{name:'email',label:'Email',type:'email'},{name:'status',label:'Status',required:true}]}/>}/>
<Route path="/processes" element={<ResourcePage resource="processes" title="Processes" description="Manage client processes and operating procedures." fields={[{name:'name',label:'Name',required:true},{name:'client_id',label:'Client ID',type:'number'},{name:'code',label:'Code'},{name:'description',label:'Description',type:'textarea'}]}/>}/>
<Route path="/teams" element={<ResourcePage resource="teams" title="Teams" description="Manage operational teams." fields={[{name:'name',label:'Name',required:true},{name:'description',label:'Description',type:'textarea'},{name:'status',label:'Status',required:true}]}/>}/>
<Route path="/queues" element={<ResourcePage resource="queues" title="Queues" description="Manage work queues and their team assignments." fields={[{name:'name',label:'Name',required:true},{name:'team_id',label:'Team ID',type:'number'},{name:'status',label:'Status',required:true}]}/>}/>
<Route path="/quality" element={<Simple title="Quality" description="QA scores, rework and sampling workflows are next in the product roadmap."/>}/>
<Route path="/workforce" element={<Workload/>}/><Route path="/automation" element={<Automation/>}/><Route path="/integrations" element={<Simple title="Integrations" description="Google Workspace, Slack, Teams and webhook connectors can be added here."/>}/><Route path="/admin" element={<Simple title="Admin" description="Organization, security, API keys and role administration belong here."/>}/>
</Route></Route>
</Routes></BrowserRouter></AuthProvider>}
