import { Table } from "react-bootstrap";
import SearchBar from "./SearchBar";
import { useState, useEffect  } from "react";

function DataGrid({ data = [], columns = [] }) {
  const [filteredData, setFilteredData] = useState(data);
  const [currentPage, setCurrentPage] = useState(1);

    useEffect(() => {
    setFilteredData(data);
    setCurrentPage(1);
  }, [data]);

  const rowsPerPage = 5;
  const totalPages = Math.ceil(filteredData.length / rowsPerPage);

  const start = (currentPage - 1) * rowsPerPage;
  const currentRows = filteredData.slice(start, start + rowsPerPage);

  return (
    <div className="p-4 bg-light rounded-4 shadow-sm">
      <div style={{ width: "85%", maxWidth: "1100px" }}>
        <SearchBar data={data} onResults={setFilteredData} />
      </div>

      <Table borderless hover responsive className="align-middle">
        <thead>
          <tr className="text-uppercase small">
            {columns.map((columna, index) => (
              <th
                key={index}
                className="fw-bold py-3"
                style={{ color: "#0A1F44" }}
              >
                {columna}
              </th>
            ))}
          </tr>
        </thead>

        <tbody>
          {filteredData.length === 0 ? (
            <tr>
              <td
                colSpan={columns.length}
                className="text-center py-4"
                style={{ color: "#4A6FA5" }}
              >
                No hay datos
              </td>
            </tr>
          ) : (
            currentRows.map((row, index) => (
              <tr key={index} className="border-top">
                {row.map((campo, i) => (
                  <td
                    key={i}
                    className="py-3 fw-medium"
                    style={{ color: "#163172" }}
                  >
                    {typeof campo === "string" &&
                    campo.toLowerCase().includes("pendiente") ? (
                      <span
                        className="badge rounded-pill px-4 py-2"
                        style={{
                          backgroundColor: "#0A1F44",
                          color: "#FFFFFF",
                        }}
                      >
                        {campo}
                      </span>
                    ) : typeof campo === "string" &&
                      campo.toLowerCase().includes("curso") ? (
                      <span
                        className="badge rounded-pill px-4 py-2"
                        style={{
                          backgroundColor: "#1E3A8A",
                          color: "#FFFFFF",
                        }}
                      >
                        {campo}
                      </span>
                    ) : (
                      campo
                    )}
                  </td>
                ))}
              </tr>
            ))
          )}
        </tbody>
      </Table>

{totalPages > 1 && (
  <div className="d-flex justify-content-center mt-3 gap-2 align-items-center">

    {currentPage > 1 && (
      <button
        onClick={() => setCurrentPage(currentPage - 1)}
        style={{
          borderRadius: "10px",
          padding: "6px 14px",
          border: "1px solid #0A1F44",
          backgroundColor: "white",
          color: "#0A1F44",
          fontWeight: "600",
          cursor: "pointer"
        }}
      >
        {"<"}
      </button>
    )}

    {Array.from({ length: totalPages }, (_, i) => i + 1)
      .slice(Math.floor((currentPage - 1) / 3) * 3, Math.floor((currentPage - 1) / 3) * 3 + 3)
      .map((page) => (
        <button
          key={page}
          onClick={() => setCurrentPage(page)}
          style={{
            borderRadius: "10px",
            padding: "6px 14px",
            border: "1px solid #0A1F44",
            backgroundColor: currentPage === page ? "#0A1F44" : "white",
            color: currentPage === page ? "white" : "#0A1F44",
            fontWeight: "600",
            cursor: "pointer"
          }}
        >
          {page}
        </button>
      ))}

    {Math.floor((currentPage - 1) / 3) * 3 + 3 < totalPages && (
      <span style={{ fontWeight: "600", color: "#0A1F44" }}>...</span>
    )}

    {currentPage < totalPages && (
      <button
        onClick={() => setCurrentPage(currentPage + 1)}
        style={{
          borderRadius: "10px",
          padding: "6px 14px",
          border: "1px solid #0A1F44",
          backgroundColor: "white",
          color: "#0A1F44",
          fontWeight: "600",
          cursor: "pointer"
        }}
      >
        {">"}
      </button>
    )}

  </div>
)}

    </div>
  );
}

export default DataGrid;