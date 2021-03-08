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
			// Get all unfinished todos or finished todo in the past 60'
			// const todos: Todo[] = this.allTodos.filter(
			// 	(todo: Todo) =>
			// 		todo.status === Status.undone ||
			// 		Date.parse(todo.updated_at || "") / 1000 >=
			// 			now
			// 				.clone()
			// 				.subtract(60, "minute")
			// 				.unix(),
			// );
			const timeLabels: string[] = this.getMinutesLabels(now.clone());
			const datasets: { x: string; y: number }[] = this.getDatasetsData(
				this.allTodos,
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

	getDatasetsData(todos: Todo[], labelArray: string[], now: any) {
		const nowTime = now.unix();
		// Number of undone todos
		const countUndones: number[] = Array(60).fill(0);
		const maxDiff: number = 59;
		for (const todo of todos) {
			const updateTime = Date.parse(todo.updated_at || "") / 1000;
			const diff = Math.min(Math.floor((nowTime - updateTime) / 60), maxDiff);
			if (todo.status === Status.undone) {
				// Undone todos caused by adding or unchecking
				++countUndones[diff];
			}
			// } else {
			// 	// Finished todos caused by checking
			// 	countUndones[diff];
			// }
		}
		const data: { x: string; y: number }[] = [];
		// Accumulate the number of adding (undone) todos from "past" (59' diff) to "now" (0' diff)
		// The sum of "59' diff" is itself
		data.push({ x: labelArray[59], y: countUndones[59] });
		for (let i = countUndones.length - 2; i >= 0; --i) {
			countUndones[i] += countUndones[i + 1];
			const time = labelArray[i];
			data.push({ x: time, y: countUndones[i] });
		}
		return data.reverse();
	}

	getMinutesLabels(now: any) {
		const res: string[] = Array(61);
		for (let i = 0; i < res.length; ++i) {
			res[i] = now.format("HH:mm");
			now.subtract(1, "minutes");
		}
		return res;
	}
}
</script>
