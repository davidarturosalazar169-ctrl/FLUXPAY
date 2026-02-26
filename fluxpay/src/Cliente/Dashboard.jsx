import "../styles.css";
import Navbar from "../Navbar";
export default function DashboardCliente() {

  return (
    <div className="page">

      {/* Sidebar */}
      <div className="side-bar">
        <div className="logo-container">
          <img 
            src="/fluxpay.jpg" 
            alt="FluxPay Logo" 
            className="logo-img" 
          />
        </div>
      </div>

      {/* Contenido principal */}
      <div className="main-content">

        {/* Navbar */}
        <Navbar
        nombre={"Alexander Castillo"}
        correo={"Alexander.Correo@Gmail"}
        rol={"Cliente"}
        >
        </Navbar>

        {/* Contenido */}
        <div className="content">
          <h1>Dashboard</h1>
        </div>

      </div>

    </div>
  );
}