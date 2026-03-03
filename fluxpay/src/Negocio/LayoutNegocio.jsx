import React from "react";
import { Outlet, NavLink } from "react-router-dom";
import {
    FaHome, FaMoneyBill, FaQrcode, FaHistory,
    FaBox, FaUsers, FaUniversity, FaChartBar,
    FaCog, FaSignOutAlt, FaBell
} from "react-icons/fa";
import "./LayoutNegocio.css";

export default function LayoutNegocio() {
    // Estilos para mantener la estructura limpia
    const activeStyle = {
        textDecoration: "none",
        display: "flex",
        alignItems: "center",
        gap: "10px",
        padding: "12px 20px", // Mantén aquí el padding exacto de tu CSS
        color: "#ffffff",
        fontWeight: "600",
        borderLeft: "4px solid #ffffff", // La línea blanca que pediste
        backgroundColor: "transparent", // Evita que se ponga azul claro
    };

    const inactiveStyle = {
        textDecoration: "none",
        display: "flex",
        alignItems: "center",
        gap: "10px",
        padding: "12px 20px 12px 24px", // 20px + 4px de la compensación del borde
        color: "rgba(255, 255, 255, 0.6)",
        borderLeft: "4px solid transparent",
        backgroundColor: "transparent",
    };

    return (
        <div className="admin-layout">
          <aside className="admin-sidebar">
                <div>
                    <div className="admin-logo-container">
                        <div className="logo-box">
                            <img src="/fluxpay.jpg" alt="FluxPay" className="logo-img" />
                        </div>
                    </div>
                    <nav>
                        <ul className="sidebar-menu">
                            <li>
                                <NavLink to="/Negocio/Dashboard">
                                    <FaHome /> <span>Dashboard</span>
                                </NavLink>
                            </li>
                            <li className="menu-item-static"><FaMoneyBill /> <span>Cobrar</span></li>
                            <li>
                                <NavLink to="/Negocio/Productos">
                                    <FaBox /> <span>Productos</span>
                                </NavLink>
                            </li>
                            <li className="menu-item-static"><FaQrcode /> <span>QR</span></li>
                            <li>
                                <NavLink to="/Negocio/Historial">
                                    <FaHistory /> <span>Historial</span>
                                </NavLink>
                            </li>
                            <li className="menu-item-static"><FaUsers /> <span>Clientes</span></li>
                            <li className="menu-item-static"><FaUniversity /> <span>Cuenta / Banco</span></li>
                            <li className="menu-item-static"><FaChartBar /> <span>Reportes</span></li>
                            <li className="menu-item-static"><FaCog /> <span>Configuración</span></li>
                        </ul>
                    </nav>
                </div>
                <div className="logout">
                    <FaSignOutAlt /> <span>Cerrar sesión</span>
                </div>
            </aside>

            {/* MAIN CONTENT AREA */}
            <main className="admin-main">
                <header className="main-header">
                    <div className="date-filter">
                        <select><option>La semana pasada</option></select>
                    </div>
                    <div className="user-profile">
                        <div className="user-info">
                            <span className="user-name">José Aguilar</span>
                            <span className="user-email">joseagui@gmail.com</span>
                        </div>

                        {/* Contenedor que permite que el punto verde sobresalga */}
                        <div className="user-avatar-container">
                            <div className="user-avatar-img-wrapper">
                                <img src="https://i.pravatar.cc/150?img=32" alt="Usuario" />
                            </div>

                            {/* La bola verde ahora sí saldrá */}
                            <div className="online-indicator"></div>
                        </div>

                        <FaBell className="notification-icon" />
                    </div>
                </header>

                <section className="view-container">
                    <Outlet />
                </section>
            </main>
        </div>
    );
}