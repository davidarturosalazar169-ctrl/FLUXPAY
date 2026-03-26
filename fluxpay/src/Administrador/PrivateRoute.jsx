import { Navigate } from "react-router-dom";

export default function ProtectedRoute({ children, rolPermitido }) {

  const token = localStorage.getItem("token");
  const user = JSON.parse(localStorage.getItem("user"));

  //  NO LOGIN
  if (!token || !user) {
    return <Navigate to="/" replace />;
  }

  //  ROL INCORRECTO
  if (rolPermitido && user.idrol !== rolPermitido) {
    return <Navigate to="/" replace />;
  }

  return children;
}