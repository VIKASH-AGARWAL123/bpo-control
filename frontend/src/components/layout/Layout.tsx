import {Outlet} from 'react-router-dom'
import Sidebar from './Sidebar';import Header from './Header'
export default function Layout(){return <div className="min-h-screen bg-slate-50"><Sidebar/><Header/><main className="pt-16 lg:ml-64"><div className="p-4 lg:p-6"><Outlet/></div></main></div>}
