import "./DashboardAdmin.css";
import Navbar from "../Navbar";
import {
  FaHome,
  FaStore,
  FaChartBar,
  FaHeadset,
  FaSignOutAlt,
} from "react-icons/fa";

export default function DashboardAdmin() {
  return (
    <div className="admin-layout">
      
      {/* SIDEBAR */}
      <aside className="admin-sidebar">

  <div className="sidebar-top">
    <div className="admin-logo-container">
      <img src="/fluxpay.jpg" alt="FluxPay Logo" className="admin-logo" />
    </div>

    <ul className="sidebar-menu">
      <li className="active"><FaHome /> <span>Dashboard</span></li>
      <li><FaStore /> <span>Gestión Negocios</span></li>
      <li><FaChartBar /> <span>Reportes globales</span></li>
      <li><FaHeadset /> <span>Soporte</span></li>
    </ul>
  </div>

  <div className="logout">
    <FaSignOutAlt />
    <span>Cerrar sesión</span>
  </div>

</aside>

      {/* CONTENIDO */}
      <div className="admin-main">
        
        <Navbar
          nombre={"Alexander Castillo"}
          correo={"Alexander.Correo@Gmail"}
          rol={"Administrador"}
        />

        <div className="admin-dashboard">

          <h1 className="admin-title">Dashboard Administrador</h1>

          {/* MÉTRICAS */}
          <div className="stats-grid">
            <div className="stat-card">
              <h4>Ventas de hoy</h4>
              <p>$12,350 <span className="positive">+8%</span></p>
            </div>

            <div className="stat-card">
              <h4>Transacciones de hoy</h4>
              <p>56 <span className="positive">+12%</span></p>
            </div>

            <div className="stat-card">
              <h4>Negocios activos</h4>
              <p>245</p>
            </div>

            <div className="stat-card">
              <h4>Ingresos del mes</h4>
              <p>$45,230 <span className="positive">+15%</span></p>
            </div>
          </div>

          {/* CENTRO */}
          <div className="middle-section">
            <div className="chart-box">
              <h3>Estadísticas recientes</h3>
              <div className="chart-placeholder">📊 Gráfica aquí</div>
            </div>

            <div className="recent-business">
              <h3>Negocios recientes</h3>
              <ul>
                <li><strong>Café el roble</strong><span>12 ventas • $3,200</span></li>
                <li><strong>Moda Express</strong><span>8 ventas • $2,150</span></li>
                <li><strong>Tienda Luna</strong><span>5 ventas • $1,100</span></li>
                <li><strong>Tech Solutions</strong><span>15 ventas • $4,500</span></li>
              </ul>
            </div>
          </div>

          {/* ACCIONES */}
          <div className="bottom-section">
            <div className="action-card">
              <h3>Gestión de Negocios</h3>
              <div className="actions">
                <button className="btn-primary">Ver negocios</button>
                <button className="btn-dark">Agregar negocio</button>
              </div>
            </div>

            <div className="action-card">
              <h3>Centro de soporte</h3>
              <div className="actions">
                <button className="btn-primary">Ver consultas</button>
                <button className="btn-dark">Nuevo ticket</button>
              </div>
            </div>
          </div>

          {/* REPORTES */}
          <div className="reports">
            <div className="report-item">👥 245 Negocios registrados</div>
            <div className="report-item">📈 8,450 Transacciones totales</div>
            <div className="report-item">💲 $324,500 Ingresos totales</div>
          </div>

        </div>
      </div>
    </div>
  );
}