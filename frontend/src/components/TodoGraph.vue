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
import LineChart from "./LineChart.vue";
import TodoDataService from "@/services/TodoDataService";
import moment from "moment";

@Component<TodoGraph>({
	components: { LineChart },
})
export default class TodoGraph extends Vue {
	@Prop({ required: true }) allTodos: any;
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
	onTodosChange(allTodos: any) {
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
			const todos = this.allTodos.filter(
				(todo: { status: number; updated_at: string }) =>
					todo.status === 0 ||
					Date.parse(todo.updated_at) / 1000 >=
						now
							.clone()
							.subtract(60, "minute")
							.unix()
			);
			const timeLabels = this.get_minutes_labels(now.clone());
			const datasets = this.get_datasets_data(todos, timeLabels, now);
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
	destroyed() {
		clearInterval(this.timer);
	}
	get_datasets_data(todos: any, label_array: any, now: any) {
		const now_time = now.unix();
		const count_done: number[] = Array(61).fill(0);
		const count_undone: number[] = Array(61).fill(0);
		for (const todo of todos) {
			if (todo.status === 0) {
				const todo_time = Date.parse(todo.updated_at) / 1000;
				const diff = Math.ceil((now_time - todo_time) / 60);
				if (diff >= 60) {
					++count_undone[60];
				} else {
					++count_undone[diff];
				}
			} else {
				const todo_time = Date.parse(todo.updated_at) / 1000;
				const diff = Math.ceil((now_time - todo_time) / 60);
				++count_done[diff];
			}
		}
		for (let i = count_undone.length - 2; i >= 0; --i) {
			count_undone[i] += count_undone[i + 1];
		}
		const data: { x: number; y: number }[] = [];
		data.push({ x: label_array[0], y: count_done[0] + count_undone[0] });
		for (let i = 1; i < count_done.length; ++i) {
			const time = label_array[i];
			const freq = count_undone[i] + count_done[i] + count_done[i - 1];
			data.push({ x: time, y: freq });
		}
		return data.reverse();
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
