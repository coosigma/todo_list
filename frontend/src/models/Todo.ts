export interface Todo {
	id: number | null;
	description: string;
	status: Status;
	created_at?: string;
	updated_at?: string;
}

export enum Status {
	undone = 0,
	done = 1,
}
