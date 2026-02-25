import { useNavigate } from "react-router-dom";
import "./styles.css";

export default function Login() {
  const navigate = useNavigate();

  const handleLogin = (e) => {
    e.preventDefault();
    alert("Inicio de sesión");
  };

  return (
    <div className="page">
      
      <div className="side-bar">
        <div className="logo-container">
          <img 
            src="/fluxpay.jpg" 
            alt="FluxPay Logo" 
            className="logo-img" 
          />
        </div>
      </div>

      <div className="content">
        <div className="content-wrapper">
          <h1 className="title">INICIAR SESIÓN</h1>

          <form className="card-login" onSubmit={handleLogin}>
            <input 
              type="email" 
              placeholder="Email" 
              className="input"
              required
            />

            <input 
              type="password" 
              placeholder="Password" 
              className="input"
              required
            />

            <button type="submit" className="btn-primary">
              Iniciar sesión
            </button>

            <button 
              type="button"
              className="btn-secondary"
              onClick={() => navigate("/register")}
            >
              Registrate
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}