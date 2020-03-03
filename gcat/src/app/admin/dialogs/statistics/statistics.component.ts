import { Component, OnInit } from "@angular/core";
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from "@angular/material";
import { Data } from "@angular/router";
import { DataService } from "src/app/services/data.service";
import * as CanvasJS from "../../../../assets/canvasjs/canvasjs.min.js";
import { EventEmitterService } from "../../event-emitter.service";

@Component({
  selector: "app-statistics",
  templateUrl: "./statistics.component.html",
  styleUrls: ["./statistics.component.scss"]
})
export class StatisticsComponent implements OnInit {
  credentials: any = {};
  credType: any = "";
  constructor(private ds: DataService, private es: EventEmitterService) {}

  ngOnInit() {
    this.credentials = JSON.parse(localStorage.getItem("gcweb_GCAT"));
    this.ds.sendRequest("getUnconfirmedCount", null).subscribe(res => {
      let unconfirmedCount = res.data[0].unconfirmedCount;
      this.ds.sendRequest("getConfirmedCount", null).subscribe(res => {
        let confirmedCount = res.data[0].confirmedCount;
        this.ds.sendRequest("getScheduledCount", null).subscribe(res => {
          let scheduledCount = res.data[0].scheduledCount;
          this.chart1(unconfirmedCount, confirmedCount, scheduledCount);
        });
      });
    });
    this.ds.sendRequest("getCountByCourse", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart2(dataPoints);
    });

    this.ds.sendRequest("getCountByCity", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart3(dataPoints);
    });

    this.ds.sendRequest("getCountBySHS", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        if (r.name == "1") {
          r.name = "SHS";
        } else {
          r.name = "HS";
        }
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart4(dataPoints);
    });

    this.ds.sendRequest("getCountByHSClass", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        if (r.name == "1") {
          r.name = "DEPED(Public)";
        } else {
          r.name = "NON-DEPED(Private)";
        }
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart5(dataPoints);
    });

    this.ds.sendRequest("getCountByStudentType", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        switch (r.name) {
          case "new":
            r.name = "FRESHMAN";
            break;
          case "second":
            r.name = "SECOND COURSER";
            break;
          case "transferee":
            r.name = "TRANSFEREE";
            break;
          default:
            r.name = r.name;
            break;
        }
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart6(dataPoints);
    });

    this.ds.sendRequest("getCountByGender", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart7(dataPoints);
    });

    this.ds.sendRequest("getCountByAge", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart8(dataPoints);
    });

    this.ds.sendRequest("getCountByCitizenship", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart9(dataPoints);
    });

    this.ds.sendRequest("getCountByCivilStatus", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart10(dataPoints);
    });

    this.ds.sendRequest("getCountByPWD", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        if (r.name == "1") {
          r.name = "YES";
        } else {
          r.name = "NO";
        }
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart11(dataPoints);
    });

    this.ds.sendRequest("getCountByDadDeceased", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        if (r.name == "1") {
          r.name = "LIVING";
        } else {
          r.name = "DECEASED";
        }
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart12(dataPoints);
    });

    this.ds.sendRequest("getCountByMomDeceased", null).subscribe(res => {
      let dataPoints = res.data.map(r => {
        if (r.name == "1") {
          r.name = "LIVING";
        } else {
          r.name = "DECEASED";
        }
        return { y: parseInt(r.y), name: r.name };
      });
      this.chart13(dataPoints);
    });
  }

  chart1(a, b, c) {
    let chart = new CanvasJS.Chart("chartContainer", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Applicant Status"
      },
      data: [
        {
          type: "pie",
          showInLegend: true,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: [
            { y: parseInt(a), name: "Unconfirmed", color: "Red" },
            { y: parseInt(b), name: "Confirmed", color: "Yellow" },
            { y: parseInt(c), name: "Scheduled", color: "Green" }
          ]
        }
      ]
    });

    chart.render();
  }

  chart2(a) {
    let chart = new CanvasJS.Chart("chartContainer2", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Desired Program(1st Choice)"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart3(a) {
    let chart = new CanvasJS.Chart("chartContainer3", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Applicant Location"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart4(a) {
    let chart = new CanvasJS.Chart("chartContainer4", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Senior High School/High School"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart5(a) {
    let chart = new CanvasJS.Chart("chartContainer5", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "DEPED/NON-DEPED"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart6(a) {
    let chart = new CanvasJS.Chart("chartContainer6", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Entrance Category"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart7(a) {
    let chart = new CanvasJS.Chart("chartContainer7", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Sex at Birth"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart8(a) {
    let chart = new CanvasJS.Chart("chartContainer8", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Age"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart9(a) {
    let chart = new CanvasJS.Chart("chartContainer9", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Citizenship"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart10(a) {
    let chart = new CanvasJS.Chart("chartContainer10", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Civil Status"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart11(a) {
    let chart = new CanvasJS.Chart("chartContainer11", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Person With Disability"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart12(a) {
    let chart = new CanvasJS.Chart("chartContainer12", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Mother"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }

  chart13(a) {
    let chart = new CanvasJS.Chart("chartContainer13", {
      theme: "light2",
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: "Father"
      },
      data: [
        {
          type: "pie",
          showInLegend: false,
          toolTipContent: "<b>{name}</b>: {y} (#percent%)",
          indexLabel: "{name} | {y} | #percent%",
          dataPoints: a
        }
      ]
    });

    chart.render();
  }
}
