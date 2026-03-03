import React from "react";
import { FaChartLine, FaBox } from "react-icons/fa";

export default function DashboardNegocio() {
  const inventoryData = [
    { id: 1, name: "Galletas", units: 10, income: "$1912" },
    { id: 2, name: "Sabritas", units: 12, income: "$1121" },
    { id: 3, name: "Refrescos", units: 6, income: "$871" },
    { id: 4, name: "Golosinas", units: 33, income: "$119" },
  ];

  return (
    <section className="admin-dashboard">
      <h2 className="admin-title">Dashboard</h2>
      <p className="welcome-text">Bienvenido Negocio</p>

      {/* STATS CARDS */}
      <div className="stats-grid">
        <div className="stat-card">
          <div className="card-header">
            <h4>Ingresos efectivo</h4>
            <FaChartLine className="chart-icon" />
          </div>
          <p className="amount">$50000.000</p>
        </div>
        <div className="stat-card">
          <div className="card-header">
            <h4>Ingresos en QR</h4>
            <FaChartLine className="chart-icon" />
          </div>
          <p className="amount">$8200.000</p>
        </div>
        <div className="stat-card">
          <div className="card-header">
            <h4>Pagos recibidos</h4>
            <FaChartLine className="chart-icon" />
          </div>
          <p className="amount">$500.000</p>
        </div>
      </div>

      {/* INVENTORY */}
      <div className="inventory-section">
        <h3 className="section-subtitle">Inventario</h3>
        <div className="inventory-container">
          <table className="inventory-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Unidades</th>
                <th>Ingresos</th>
              </tr>
            </thead>
            <tbody>
              {inventoryData.map((item) => (
                <tr key={item.id}>
                  <td className="product-cell">
                    <FaBox className="prod-img" />
                    {item.name}
                  </td>
                  <td>{item.units}</td>
                  <td className="income-text">{item.income}</td>
                </tr>
              ))}
            </tbody>
          </table>

          <div className="category-chart">
            <p className="chart-title">Ventas por categoría</p>
            <div className="bars-comparison">
              <div className="bar-group">
                <div className="bar color-1" style={{ height: "80%" }}></div>
                <div className="bar color-2" style={{ height: "50%" }}></div>
              </div>
            </div>
            <div className="chart-legend">
              <span><span className="dot d1"></span> Efectivo</span>
              <span><span className="dot d2"></span> QR</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}