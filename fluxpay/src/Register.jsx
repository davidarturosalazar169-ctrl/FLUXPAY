import { useNavigate } from "react-router-dom";
import "./styles.css";

export default function Register() {
  const navigate = useNavigate();

  const handleRegister = (e) => {
    e.preventDefault();
    alert("Cuenta creada correctamente");
  };

  return (
    <div className="page">

      {/* SIDEBAR CON LOGO */}
      <div className="side-bar">
        <div className="logo-container">
          <img 
            src="/fluxpay.jpg" 
            alt="FluxPay Logo" 
            className="logo-img" 
          />
        </div>
      </div>

      {/* CONTENIDO */}
      <div className="content">
        <h1 className="title">REGISTRARSE</h1>

        <form className="card-register" onSubmit={handleRegister}>

          <div className="form-grid">
            <input 
              type="email" 
              placeholder="Correo" 
              className="input"
              required
            />

            <input 
              type="text" 
              placeholder="Nombre" 
              className="input"
              required
            />

            <input 
              type="password" 
              placeholder="Contraseña" 
              className="input"
              required
            />

            <input 
              type="text" 
              placeholder="Banco" 
              className="input"
              required
            />

            <input 
              type="text" 
              placeholder="Nombre negocio" 
              className="input"
            />

            <input 
              type="text" 
              placeholder="Cuenta bancaria" 
              className="input"
            />
          </div>

          <button type="submit" className="btn-primary large">
            Crear Cuenta
          </button>

          <button 
            type="button"
            className="btn-secondary"
            onClick={() => navigate("/")}
          >
            Volver al Login
          </button>

        </form>
      </div>
    </div>
  );
}