export type User={id:number;name:string;email:string;role:string;organization_id:number;is_active:boolean;organization?:Organization}
export type Organization={id:number;name:string;slug:string;timezone:string}
export type Task={id:number;task_number:string;title:string;description?:string;client_id?:number;process_id?:number;team_id?:number;queue_id?:number;assignee_id?:number;status:string;priority:string;sla_status:string;due_at?:string;completed_at?:string}
export type Resource={id:number;name:string;code?:string;email?:string;description?:string;status:string;client_id?:number;team_id?:number}
export type Dashboard={stats:Record<string,number>;recent_tasks:Task[];status_breakdown:Record<string,number>;sla_breakdown:Record<string,number>}
export type Automation={id:number;name:string;trigger:string;conditions?:Record<string,unknown>;actions:unknown[];enabled:boolean}
