package Models

type NodeType string

const (
	Joint NodeType = "JOINT"
	U1U2  NodeType = "U1U2"
	U1    NodeType = "U1"
	U2    NodeType = "U2"
	Free  NodeType = "FREE"
)
