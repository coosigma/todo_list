export interface Todo {
	id: number | null;
	description: string;
	status: number;
	created_at?: string;
	updated_at?: string;
}
