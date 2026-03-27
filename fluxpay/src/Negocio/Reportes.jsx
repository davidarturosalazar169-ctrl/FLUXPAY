import React, { useRef, useState, useMemo } from 'react';
import { 
  FaSearch, FaPaperclip, FaArrowUp, FaArrowDown, 
  FaCheckCircle, FaTrashAlt, FaFileAlt 
} from 'react-icons/fa';

const Reportes = () => {
  const fileInputRef = useRef(null);
  const [fileName, setFileName] = useState("");
  const [searchTerm, setSearchTerm] = useState(""); 

  // Datos de ejemplo con más información para FluxPay
  const [reportesData] = useState([
    { id: "9132102", estado: "Pendiente", fecha: "2026-03-25", monto: "$1,200" },
    { id: "245533", estado: "Pendiente", fecha: "2026-03-26", monto: "$450" },
    { id: "1232455", estado: "Pendiente", fecha: "2026-03-27", monto: "$2,100" },
    { id: "1213231", estado: "Revisado", fecha: "2026-03-20", monto: "$890" },
  ]);

  // Optimización de búsqueda con useMemo
  const reportesFiltrados = useMemo(() => {
    return reportesData.filter(item => 
      item.id.toLowerCase().includes(searchTerm.toLowerCase())
    );
  }, [searchTerm, reportesData]);

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      // Validación: solo permitir imágenes o PDFs para evidencias
      if (file.size > 5000000) { // 5MB limit
        alert("El archivo es demasiado grande.");
        return;
      }
      setFileName(file.name);
    }
  };

  const removeFile = (e) => {
    e.stopPropagation(); 
    setFileName("");
    if (fileInputRef.current) fileInputRef.current.value = "";
  };

  return (
    <section className="reportes-view" style={{ padding: '20px', backgroundColor: '#f8fafc' }}>
      {/* 1. TÍTULO Y ENCABEZADO */}
      <div className="reportes-title-section" style={{ marginBottom: '30px' }}>
        <h2 className="section-main-title" style={{ color: '#0e2a5a', fontWeight: 'bold' }}>Reportes Operativos</h2>
        <p className="section-subtitle" style={{ color: '#64748b' }}>Supervisión de transacciones y carga de evidencias FluxPay.</p>
      </div>

      {/* 2. TABLA DE MÉTRICAS OPERATIVAS */}
      <div className="metrics-container" style={{ marginBottom: '30px' }}>
        <div className="metrics-table" style={{ background: '#fff', borderRadius: '15px', padding: '20px', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}>
          <div className="metrics-header" style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', paddingBottom: '10px', borderBottom: '1px solid #e2e8f0', fontWeight: '600', color: '#64748b' }}>
            <span>Indicador</span>
            <span>Valor Actual</span>
            <span>Rendimiento</span>
          </div>
          
          {[
            { label: "Ingresos Totales", value: "$25,400.00", trend: "12.5%", pos: true },
            { label: "Cobros vía QR", value: "145 ops", trend: "5.2%", pos: true },
            { label: "Nuevos Clientes", value: "22 pax", trend: "2.1%", pos: false },
          ].map((metric, idx) => (
            <div key={idx} className="metric-row" style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', padding: '15px 0', borderBottom: idx !== 2 ? '1px solid #f1f5f9' : 'none', alignItems: 'center' }}>
              <span className="metric-name" style={{ fontWeight: '500' }}>{metric.label}</span>
              <span className="metric-value" style={{ fontWeight: 'bold', color: '#0e2a5a' }}>{metric.value}</span>
              <span className={`trend ${metric.pos ? 'positive' : 'negative'}`} style={{ color: metric.pos ? '#10b981' : '#ef4444', display: 'flex', alignItems: 'center', gap: '5px' }}>
                {metric.pos ? <FaArrowUp /> : <FaArrowDown />} {metric.trend}
              </span>
            </div>
          ))}
        </div>
      </div>

      {/* 3. ACCIONES Y LISTADO */}
      <div className="reportes-grid-bottom" style={{ display: 'grid', gridTemplateColumns: '1fr 1.5fr', gap: '25px' }}>
        
        {/* BUSCADOR Y ADJUNTAR */}
        <div className="action-card search-card" style={{ background: '#fff', padding: '25px', borderRadius: '15px', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}>
          <h3 className="card-mini-title" style={{ fontSize: '1.1rem', marginBottom: '20px', color: '#0e2a5a' }}>Gestión de Casos</h3>
          
          <div className="search-input-wrapper" style={{ position: 'relative', marginBottom: '20px' }}>
            <FaSearch style={{ position: 'absolute', left: '15px', top: '15px', color: '#94a3b8' }} />
            <input 
              type="text" 
              placeholder="Buscar por ID..." 
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="modern-input"
              style={{ width: '100%', padding: '12px 12px 12px 45px', borderRadius: '10px', border: '1px solid #e2e8f0', outline: 'none' }}
            />
          </div>

          <div className="upload-container">
            <input 
              type="file" 
              ref={fileInputRef} 
              onChange={handleFileChange} 
              accept=".jpg,.png,.pdf"
              style={{ display: 'none' }} 
            />
            <div 
              className={`upload-zone ${fileName ? 'active' : ''}`} 
              onClick={() => fileInputRef.current.click()}
              style={{ 
                border: '2px dashed #cbd5e1', 
                borderRadius: '12px', 
                padding: '30px', 
                textAlign: 'center', 
                cursor: 'pointer',
                transition: 'all 0.3s ease',
                backgroundColor: fileName ? '#f0fdf4' : 'transparent',
                borderColor: fileName ? '#22c55e' : '#cbd5e1'
              }}
            >
              {fileName ? (
                <div className="file-info-pro" style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '10px' }}>
                  <FaCheckCircle style={{ color: '#22c55e' }} />
                  <span style={{ maxWidth: '150px', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{fileName}</span>
                  <button onClick={removeFile} style={{ border: 'none', background: 'none', color: '#ef4444', cursor: 'pointer' }}>
                    <FaTrashAlt />
                  </button>
                </div>
              ) : (
                <div style={{ color: '#64748b' }}>
                  <FaPaperclip style={{ fontSize: '1.5rem', marginBottom: '10px' }} />
                  <p style={{ margin: 0 }}>Adjuntar Evidencia</p>
                  <small>(JPG, PNG o PDF)</small>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* TABLA DE REPORTES */}
        <div className="action-card table-card" style={{ background: '#fff', padding: '25px', borderRadius: '15px', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}>
          <h3 className="card-mini-title" style={{ fontSize: '1.1rem', marginBottom: '20px', color: '#0e2a5a' }}>Movimientos Recientes</h3>
          <div className="table-responsive">
            <table style={{ width: '100%', borderCollapse: 'collapse' }}>
              <thead>
                <tr style={{ textAlign: 'left', borderBottom: '2px solid #f1f5f9', color: '#64748b' }}>
                  <th style={{ padding: '12px' }}>Caso ID</th>
                  <th style={{ padding: '12px' }}>Monto</th>
                  <th style={{ padding: '12px' }}>Estado</th>
                </tr>
              </thead>
              <tbody>
                {reportesFiltrados.length > 0 ? (
                  reportesFiltrados.map((item) => (
                    <tr key={item.id} style={{ borderBottom: '1px solid #f1f5f9' }}>
                      <td style={{ padding: '15px 12px' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                          <FaFileAlt style={{ color: '#3b82f6' }} />
                          <strong>#{item.id}</strong>
                        </div>
                      </td>
                      <td style={{ padding: '15px 12px', fontWeight: '600' }}>{item.monto}</td>
                      <td style={{ padding: '15px 12px' }}>
                        <span style={{ 
                          padding: '5px 12px', 
                          borderRadius: '20px', 
                          fontSize: '0.85rem',
                          fontWeight: '500',
                          backgroundColor: item.estado === 'Revisado' ? '#dcfce7' : '#fef9c3',
                          color: item.estado === 'Revisado' ? '#166534' : '#854d0e'
                        }}>
                          {item.estado}
                        </span>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="3" style={{ textAlign: 'center', padding: '40px', color: '#94a3b8' }}>
                      No se encontraron reportes con el ID: <strong>{searchTerm}</strong>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Reportes;