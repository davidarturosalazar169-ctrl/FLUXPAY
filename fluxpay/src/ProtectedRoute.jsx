import { Navigate } from "react-router-dom";

export default function ProtectedRoute({ children, rolPermitido }) {

  const token = localStorage.getItem("token");

  //  sin token → fuera
  if (!token) {
    return <Navigate to="/" replace />;
  }

  let user = null;

  try {
    const storedUser = localStorage.getItem("user");
    user = storedUser ? JSON.parse(storedUser) : null;
  } catch (error) {
    return <Navigate to="/" replace />;
  }

  // sin user → fuera
  if (!user) {
    return <Navigate to="/" replace />;
  }

  // sin rol → fuera
  if (!user.idrol) {
    return <Navigate to="/" replace />;
  }

  // rol incorrecto → fuera
  if (rolPermitido && user.idrol !== rolPermitido) {
    return <Navigate to="/" replace />;
  }

  return children;
}