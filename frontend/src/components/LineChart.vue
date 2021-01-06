<script lang="ts">
import { Line, mixins } from "vue-chartjs";
import { Component, Prop, Vue } from "vue-property-decorator";
const { reactiveProp } = mixins;

@Component({
	extends: Line,
	mixins: [reactiveProp],
})
export default class LineChart extends Vue {
	@Prop({ default: null })
	chartData!: object;
	@Prop({ default: null }) options!: object;
	mounted() {
		this.renderChart(this.chartData, this.options);
	}
	public renderChart!: (chartData: any, options: any) => void;
}
</script>
