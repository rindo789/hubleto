import React, { Component } from "react";
import Form, { FormDescription, FormProps, FormState } from "adios/Form";
import { Bar, Doughnut, Pie, Line } from "react-chartjs-2";
import { Chart as ChartJS, ArcElement, Tooltip, Legend, BarController, BarElement, CategoryScale, LinearScale, PointElement, LineElement, LineController } from "chart.js";

ChartJS.register(ArcElement, Tooltip, Legend, BarController, BarElement, CategoryScale, LinearScale, PointElement, LineElement, LineController);

export type HubletoChartType = 'bar' | 'doughnut' | 'pie' | 'goals';

export interface HubletoChartProps {
  type: HubletoChartType,
  data: any,
  legend?: any,
}

export interface HubletoChartState {}

export default class HubletoChart<P, S> extends Component<HubletoChartProps,HubletoChartState> {
  props: HubletoChartProps;
  state: HubletoChartState;

  constructor(props: HubletoChartProps) {
    super(props);
  }

  render(): JSX.Element {
    console.log('hubletoChart data', this.props.data);
    switch (this.props.type) {
      case "bar":
        return (
          <Bar
            width={0}
            height={0}
            options={{
              scales: {
                x: {
                  ticks: {
                    display: false,
                  },
                },
                y: {
                  beginAtZero: true,
                },
              },
            }}
            data={{
              labels: this.props.data != null ? [...this.props.data.labels] : [],
              datasets: [
                {
                  data: this.props.data != null ? [...this.props.data.values] : [],
                  backgroundColor: this.props.data != null ? [...this.props.data.colors] : [],
                },
              ],
            }}
          />
        );
      case "doughnut":
        return (
          <div className="h-full m-auto relative">
            <Doughnut
              options={{
                responsive: true,
                plugins: {
                  legend: this.props.legend ? this.props.legend : {
                    display: false,
                  },
                },
              }}
              data={{
                labels: this.props.data ? [...this.props.data.labels] : [],
                datasets: [
                  {
                    data: this.props.data ? [...this.props.data.values] : [],
                    backgroundColor: this.props.data != null ? [...this.props.data.colors] : [],
                  },
                ],
              }}
            />
          </div>
        );
      case "pie":
        return (
          <div className="h-full w-full m-auto relative">
            <Pie
              options={{
                responsive: true,
                aspectRatio: 2,
                plugins: {
                  legend: this.props.legend ? this.props.legend : {
                    display: false,
                  },
                },
              }}
              data={{
                labels: this.props.data ? [...this.props.data.labels] : [],
                datasets: [
                  {
                    data: this.props.data ? [...this.props.data.values] : [],
                    backgroundColor: this.props.data != null ? [...this.props.data.colors] : [],
                  },
                ],
              }}
            />
          </div>
        );
      case "goals":
        return <Bar
          data={{
            datasets: [
              {
                type: 'line',
                label: 'Goals',
                backgroundColor: "#ffb12b",
                borderColor: "#a87316",
                data: this.props.data ? [...this.props.data.goals] : [],
              },
              {
                type: 'bar',
                label: 'Won Deals',
                backgroundColor: "#66c24f",
                data: this.props.data ? [...this.props.data.won] : [],
                stack: "stack"
              },
              {
                type: 'bar',
                label: 'Pending Deals',
                backgroundColor: "#cfcecc",
                data: this.props.data ? [...this.props.data.pending] : [],
                stack: "stack"
              },

            ],
            labels: this.props.data ? [...this.props.data.labels] : [],
          }}
      />;
      default:
        return <></>;
    }
  }
}
