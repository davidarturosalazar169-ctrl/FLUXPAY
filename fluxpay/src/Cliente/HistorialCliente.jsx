import "../styles.css";
import Navbar from "../Navbar";
import "bootstrap/dist/css/bootstrap.min.css";
import { useState } from "react";
import TarjetasCliente from "./TarjetasCliente";
import { Button, Card, Row, Col } from "react-bootstrap";
import PaymentForm from "./PaymentForm";
import DataGrid from "../DataGrid";
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
import { useNavigate } from "react-router-dom";

const HistorialCliente = () => {
  const navigate = useNavigate();

    return (
    <div className="page">
    {/* Sidebar */}
      
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
    <div style={{ width: "400px", margin: "60px auto 0 auto", textAlign: "center" }}>
        <h1>Historial de compras</h1>
    </div>
    <div>
    {/* Tabla de contenido */}
    <div className="container d-flex justify-content-center mt-4">
    <div style={{ width: "85%", maxWidth: "1100px" }}>
<DataGrid
columns={["Fecha", "Concepto", "Monto", "Estado"]}
data={[
["2/12/2024", "Comida", "MXN 1912", "Pendiente"],
["2/03/2024", "Pago", "MXN 1912", "Pendiente"],
["5/09/2025", "Tienda", "MXN 1912", "Pendiente"],
["2/01/2026", "Disp", "MXN 1912", "Pendiente"],
["4/02/2026", "Supermercado", "MXN 850", "Pendiente"],
["7/02/2026", "Gasolina", "MXN 1200", "Pendiente"],
["10/02/2026", "Amazon", "MXN 450", "Pendiente"],
["11/02/2026", "Uber", "MXN 120", "Pendiente"],
["15/02/2026", "Netflix", "MXN 189", "Pendiente"],
["18/02/2026", "Spotify", "MXN 129", "Pendiente"],
["22/02/2026", "Mercado", "MXN 640", "Pendiente"],
["25/02/2026", "Farmacia", "MXN 210", "Pendiente"],
]}
/>
    </div>
    </div>
    </div>
    </div>
    </div>
    )
}

export default HistorialCliente