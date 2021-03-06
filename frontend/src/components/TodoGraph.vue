<template>
	<div class="container">
		<div v-if="unauthorized" class="col-md-10">
			<div class="alert alert-danger" style="margin-top: 10%;">
				<strong>Unauthorized!</strong> Your token may have expired. You can try
				to log out and log in again.
			</div>
		</div>
		<line-chart v-if="loaded" :chartData="chartData" :options="options" />
	</div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch } from "vue-property-decorator";
import { Todo, Status } from "@/models/Todo";
import LineChart from "./LineChart.vue";
import moment from "moment";

@Component<TodoGraph>({
	components: { LineChart },
})
export default class TodoGraph extends Vue {
	@Prop({ required: true }) allTodos!: Todo[];
	public loaded: boolean = false;
	public chartData: any = null;
	public maxY = 0;
	public timer = 0;
	public firstTime = true;
	public unauthorized = false;
	public options = {
		title: {
			display: true,
			text: "Unfinished Todos",
		},
		scales: {
			yAxes: [
				{
					ticks: {
						beginAtZero: true,
						callback: function(value: number) {
							// Only reuturn integer ticks
							if (value % 1 === 0) {
								return value;
							}
						},
					},
				},
			],
		},
	};

	@Watch("allTodos", { immediate: true, deep: true })
	onTodosChange(allTodos: Todo[]) {
		this.loadData();
	}

	mounted() {
		if (this.firstTime) {
			this.loadData();
			this.firstTime = false;
		}
	}

	async loadData() {
		try {
			const now = moment();
			// Get all unfinished todos
			const todos: Todo[] = this.allTodos.filter(
				(todo: Todo) => todo.status === Status.undone,
			);
			const timeLabels: string[] = this.get_minutes_labels(now.clone());
			const datasets: { x: string; y: number }[] = this.get_datasets_data(
				todos,
				timeLabels,
				now,
			);
			this.chartData = {
				labels: timeLabels.reverse(),
				datasets: [
					{
						label: "# of Todos",
						data: datasets,
						borderColor: "blue",
						borderWidth: 1,
					},
				],
			};
			this.loaded = true;
		} catch (error) {
			if (error.response.status === 401) {
				this.unauthorized = true;
			}
			console.log(error);
		}
	}

	get_datasets_data(todos: Todo[], label_array: string[], now: any) {
		const now_time = now.unix();
		const count_undone: number[] = Array(61).fill(0);
		for (const todo of todos) {
			if (todo.status === Status.undone) {
				const todo_time = Date.parse(todo.updated_at || "") / 1000;
				const diff = Math.ceil((now_time - todo_time) / 60);
				if (diff >= 60) {
					// Unfinished todos whose update time is equal to or more than 60 minutes
					++count_undone[60];
				} else {
					// Unfinished todos with an update time of less than 60 minutes
					++count_undone[diff];
				}
			}
		}
		const data: { x: string; y: number }[] = [];
		// Accumulate the number of adding (undone) todos from "past" (59' diff) to "now" (0' diff)
		// The sum of "60' diff" is itself
		data.push({ x: label_array[60], y: count_undone[60] });
		for (let i = count_undone.length - 2; i >= 0; --i) {
			count_undone[i] += count_undone[i + 1];
			const time = label_array[i];
			data.push({ x: time, y: count_undone[i] });
		}
		return data;
	}

	get_minutes_labels(now: any) {
		const res: string[] = Array(61);
		for (let i = 0; i < res.length; ++i) {
			res[i] = now.format("HH:mm");
			now.subtract(1, "minutes");
		}
		return res;
	}
}
</script>
