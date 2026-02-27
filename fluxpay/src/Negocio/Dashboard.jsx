import React from "react";
import "./Dashboard.css";

export default function Dashboard() {
  return (
    <div className="layout">
      
      {/* SIDEBAR */}
      <aside className="sidebar">
        <div className="logo">FluxPay</div>
      
        <nav>
          <ul>
            <li className="active">Dashboard</li>
            <li>Cobrar</li>
            <li>QR</li>
            <li>Historial</li>
            <li>Productos</li>
            <li>Clientes</li>
            <li>Cuenta/Banco</li>
            <li>Reportes</li>
            <li>Configuración</li>
          </ul>
        </nav>

        <button className="logout">Cerrar sesión</button>
      </aside>

      {/* MAIN */}
      <main className="main">
        <header className="header">
          <h2>Dashboard</h2>
          <div className="user">
            <span>José Aguilar</span>
          </div>
        </header>

        {/* CARDS */}
        <section className="cards">
          <div className="card">
            <h4>Ingresos efectivo</h4>
            <p>$50,000.000</p>
          </div>

          <div className="card">
            <h4>Ingresos QR</h4>
            <p>$8,200.000</p>
          </div>

          <div className="card">
            <h4>Pagos recibidos</h4>
            <p>$500.000</p>
          </div>
        </section>

        {/* INVENTARIO */}
        <section className="inventory">
          <h3>Inventario</h3>
          <table>
            <thead>
              <tr>
                <th>Producto</th>
                <th>Unidades</th>
                <th>Ingresos</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Galletas</td>
                <td>10</td>
                <td>$1912</td>
              </tr>
              <tr>
                <td>Sabritas</td>
                <td>12</td>
                <td>$1121</td>
              </tr>
              <tr>
                <td>Refrescos</td>
                <td>6</td>
                <td>$871</td>
              </tr>
              <tr>
                <td>Golosinas</td>
                <td>33</td>
                <td>$119</td>
              </tr>
            </tbody>
          </table>
        </section>

      </main>
    </div>
  );
}