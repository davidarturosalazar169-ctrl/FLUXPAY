import "../styles.css";
import Navbar from "../Navbar";
import "bootstrap/dist/css/bootstrap.min.css";
import "../Administrador/DashboardAdmin.css";
import "./Cliente.css";
import { useNavigate } from "react-router-dom";
import "../Styles/StyleNavbar.css";
import {
  FaHome,
  FaStore,
  FaChartBar,
  FaHeadset,
  FaSignOutAlt,
  FaSearch,
  FaBell,
  FaDollarSign,
  FaShoppingCart,
  FaUsers,
  FaHistory,
  FaCog, 
} from "react-icons/fa";
import { CiCreditCard1 } from "react-icons/ci";

export default function DashboardCliente() {
  const navigate = useNavigate();
  return (
    <div className="page">
      
      {/* Sidebar */}
<aside className="admin-sidebar">
  <div>
    <div className="admin-logo-container">
      <img src="/fluxpay.jpg" alt="FluxPay Logo" className="admin-logo" />
    </div>

    <ul className="sidebar-menu">
      
      <li 
        className="active"
        onClick={() => navigate("/dashboard")} 
        style={{ cursor: "pointer" }}
      >
        <FaHome /> Dashboard
      </li>

      <li 
        onClick={() => navigate("/Cliente/clienteTarjetas")} 
        style={{ cursor: "pointer" }}
      >
        <CiCreditCard1 /> Mis Tarjetas
      </li>

      <li 
        onClick={() => navigate("/Cliente/HistorialCliente")} 
        style={{ cursor: "pointer" }}
      >
        <FaHistory /> Historial
      </li>

      <li 
        onClick={() => navigate("/Cliente/ClienteConfiguracion")} 
        style={{ cursor: "pointer" }}
      >
        <FaCog /> Configuración
      </li>

    </ul>
  </div>

  <div className="logout">
    <FaSignOutAlt /> Cerrar sesión
  </div>
</aside>
      
      {/* Contenido principal */}
      <div className="main-content">
<div className="container-fluid px-4 pt-4">
  <div className="bg-white shadow rounded-4 p-3">
    <Navbar
      nombre="Alexander Castillo"
      correo="Alexander.Correo@Gmail.com"
      rol="Cliente"
    />
  </div>
</div>
        <div className="container center">

          <div className="row mt-4">
        <div className="row mt-4 g-4">

  {/* Cuenta Bancaria */}
  <div className="col-12 col-md-6">
    <div 
      className="card dashboard-card cuenta-card"
      onClick={() => navigate("/Cliente/clienteTarjetas")}
      style={{ cursor: "pointer" }}
    >
      <div className="card-body text-center">

        <div className="dashboard-icon mb-3">
          <CiCreditCard1 size={45} />
        </div>

        <h5 className="fw-bold">Mis Tarjetas</h5>
        <p className="text-muted small">
          Administra tus tarjetas y métodos de pago
        </p>

      </div>
    </div>
  </div>

  {/* Historial */}
  <div className="col-12 col-md-6">
    <div 
      className="card dashboard-card cuenta-card"
      onClick={() => navigate("/Cliente/HistorialCliente")}
      style={{ cursor: "pointer" }}
    >
      <div className="card-body text-center">

        <div className="dashboard-icon mb-3">
          <FaHistory size={40} />
        </div>

        <h5 className="fw-bold">Historial de Pagos</h5>
        <p className="text-muted small">
          Consulta todas tus transacciones
        </p>

      </div>
    </div>
  </div>

</div>


          </div>
        </div>

      </div>
    </div>
  );
}