import {createContext,useContext,useEffect,useState} from 'react'
import type {ReactNode} from 'react'
import api from '../services/api'
import type {User,Organization} from '../types'
type AuthContextType={user:User|null;organization:Organization|null;loading:boolean;signin:(email:string,password:string)=>Promise<void>;signup:(data:{organization_name:string;name:string;email:string;password:string;password_confirmation:string})=>Promise<void>;logout:()=>Promise<void>}
const AuthContext=createContext<AuthContextType|null>(null)
export function AuthProvider({children}:{children:ReactNode}){const [user,setUser]=useState<User|null>(null);const [organization,setOrganization]=useState<Organization|null>(null);const [loading,setLoading]=useState(true)
useEffect(()=>{const load=async()=>{const token=localStorage.getItem('bpo_token');if(!token){setLoading(false);return}try{const {data}=await api.get('/auth/me');setUser(data.user);setOrganization(data.user.organization??null)}catch{}finally{setLoading(false)}};load();const handler=()=>{setUser(null);setOrganization(null)};window.addEventListener('auth:logout',handler);return()=>window.removeEventListener('auth:logout',handler)},[])
const signin=async(email:string,password:string)=>{const {data}=await api.post('/auth/signin',{email,password});localStorage.setItem('bpo_token',data.access_token);setUser(data.user);setOrganization(data.organization)}
const signup=async(payload:{organization_name:string;name:string;email:string;password:string;password_confirmation:string})=>{const {data}=await api.post('/auth/signup',payload);localStorage.setItem('bpo_token',data.access_token);setUser(data.user);setOrganization(data.organization)}
const logout=async()=>{try{await api.post('/auth/logout')}finally{localStorage.removeItem('bpo_token');setUser(null);setOrganization(null)}}
return <AuthContext.Provider value={{user,organization,loading,signin,signup,logout}}>{children}</AuthContext.Provider>}
export const useAuth=()=>{const ctx=useContext(AuthContext);if(!ctx)throw new Error('useAuth must be used inside AuthProvider');return ctx}
